<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Enums\RevenueTypeEnum;
use Botble\Marketplace\Enums\WithdrawalStatusEnum;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Http\Requests\Fronts\BecomeVendorRequest;
use Botble\Marketplace\Models\Revenue;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\Withdrawal;
use Botble\Media\Chunks\Exceptions\UploadMissingFileException;
use Botble\Media\Chunks\Handler\DropZoneUploadHandler;
use Botble\Media\Chunks\Receiver\FileReceiver;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends BaseController
{
    public function __construct()
    {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css');

        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery'])
            ->add('cropper-js', 'vendor/core/plugins/ecommerce/libraries/cropper.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);
    }

    public function index(Request $request)
    {
        $this->pageTitle(__('Dashboard'));

        Assets::addScriptsDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.js',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.min.js',
                'vendor/core/plugins/ecommerce/js/report.js',
            ])
            ->addStylesDirectly([
                'vendor/core/plugins/ecommerce/libraries/daterangepicker/daterangepicker.css',
                'vendor/core/plugins/ecommerce/libraries/apexcharts-bundle/dist/apexcharts.css',
                'vendor/core/plugins/ecommerce/css/report.css',
            ])
            ->addScripts(['moment']);

        Assets::usingVueJS();

        [$startDate, $endDate, $predefinedRange] = EcommerceHelper::getDateRangeInReport($request);

        $user = auth('customer')->user();
        $store = $user->store;
        $data = compact('startDate', 'endDate', 'predefinedRange');

        $revenue = Revenue::query()
            ->selectRaw(
                'SUM(CASE WHEN type IS NULL OR type = ? THEN sub_amount WHEN type = ? THEN sub_amount * -1 ELSE 0 END) as sub_amount,
                SUM(CASE WHEN type IS NULL OR type = ? THEN amount WHEN type = ? THEN amount * -1 ELSE 0 END) as amount,
                SUM(fee) as fee',
                [RevenueTypeEnum::ADD_AMOUNT, RevenueTypeEnum::SUBTRACT_AMOUNT, RevenueTypeEnum::ADD_AMOUNT, RevenueTypeEnum::SUBTRACT_AMOUNT]
            )
            ->where('customer_id', $user->getKey())
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('customer_id')
            ->first();

        $withdrawal = Withdrawal::query()
            ->select([
                DB::raw('SUM(mp_customer_withdrawals.amount) as amount'),
                DB::raw('SUM(mp_customer_withdrawals.fee)'),
            ])
            ->where('mp_customer_withdrawals.customer_id', $user->getKey())
            ->whereIn('mp_customer_withdrawals.status', [
                WithdrawalStatusEnum::COMPLETED,
                WithdrawalStatusEnum::PENDING,
                WithdrawalStatusEnum::PROCESSING,
            ])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereDate('mp_customer_withdrawals.created_at', '>=', $startDate)
                    ->whereDate('mp_customer_withdrawals.created_at', '<=', $endDate);
            })
            ->groupBy('mp_customer_withdrawals.customer_id')
            ->first();

        $revenues = collect([
            'amount' => $revenue ? $revenue->amount : 0,
            'fee' => ($revenue ? $revenue->fee : 0) + ($withdrawal ? $withdrawal->fee : 0),
            'sub_amount' => $revenue ? $revenue->sub_amount : 0,
            'withdrawal' => $withdrawal ? $withdrawal->amount : 0,
        ]);

        $data['revenue'] = $revenues;

        $data['orders'] = Order::query()
            ->select([
                'id',
                'status',
                'user_id',
                'created_at',
                'amount',
                'tax_amount',
                'shipping_amount',
                'payment_id',
            ])
            ->with(['user', 'payment'])
            ->where([
                'is_finished' => 1,
                'store_id' => $store->id,
            ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $data['products'] = Product::query()
            ->select([
                'id',
                'name',
                'order',
                'created_at',
                'status',
                'sku',
                'images',
                'price',
                'sale_price',
                'sale_type',
                'start_date',
                'end_date',
                'quantity',
                'with_storehouse_management',
            ])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->where([
                'is_variation' => false,
                'store_id' => $store->id,
            ])
            ->wherePublished()
            ->limit(10)
            ->get();

        $totalProducts = $store->products()->count();
        $totalOrders = $store->orders()->count();
        $compact = compact('user', 'store', 'data', 'totalProducts', 'totalOrders');

        if ($request->ajax()) {
            return $this
                ->httpResponse()
                ->setData([
                    'html' => MarketplaceHelper::view('vendor-dashboard.partials.dashboard-content', $compact)->render(),
                ]);
        }

        return MarketplaceHelper::view('vendor-dashboard.index', $compact);
    }

    public function postUpload(Request $request)
    {
        $customer = auth('customer')->user();

        $uploadFolder = $customer->store?->upload_folder ?: $customer->upload_folder;

        if (! RvMedia::isChunkUploadEnabled()) {
            $validator = Validator::make($request->all(), [
                'file.0' => 'required|image|mimes:jpg,jpeg,png',
            ]);

            if ($validator->fails()) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($validator->getMessageBag()->first());
            }

            $result = RvMedia::handleUpload(Arr::first($request->file('file')), 0, $uploadFolder);

            if ($result['error']) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }

            return $this
                ->httpResponse()
                ->setData($result['data']);
        }

        try {
            // Create the file receiver
            $receiver = new FileReceiver('file', $request, DropZoneUploadHandler::class);
            // Check if the upload is success, throw exception or return response you need
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }
            // Receive the file
            $save = $receiver->receive();
            // Check if the upload has finished (in chunk mode it will send smaller files)
            if ($save->isFinished()) {
                $result = RvMedia::handleUpload($save->getFile(), 0, $uploadFolder);

                if (! $result['error']) {
                    return $this
                        ->httpResponse()
                        ->setData($result['data']);
                }

                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }
            // We are in chunk mode, lets send the current progress
            $handler = $save->handler();

            return response()->json([
                'done' => $handler->getPercentageDone(),
                'status' => true,
            ]);
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function postUploadFromEditor(Request $request)
    {
        $customer = auth('customer')->user();

        $uploadFolder = $customer->store?->upload_folder ?: $customer->upload_folder;

        return RvMedia::uploadFromEditor($request, 0, $uploadFolder);
    }

    public function getBecomeVendor()
    {
        if (! MarketplaceHelper::isVendorRegistrationEnabled()) {
            abort(404);
        }

        $customer = auth('customer')->user();
        if ($customer->is_vendor) {
            if (MarketplaceHelper::getSetting('verify_vendor', 1) && ! $customer->vendor_verified_at) {
                SeoHelper::setTitle(__('Become Vendor'));

                Theme::breadcrumb()
                    ->add(__('Approving'));

                return Theme::scope('marketplace.approving-vendor', [], MarketplaceHelper::viewPath('approving-vendor', false))
                    ->render();
            }

            return redirect()->route('marketplace.vendor.dashboard');
        }

        SeoHelper::setTitle(__('Become Vendor'));

        Theme::breadcrumb()
            ->add(__('Become Vendor'), route('marketplace.vendor.become-vendor'));

        return Theme::scope('marketplace.become-vendor', [], MarketplaceHelper::viewPath('become-vendor', false))
            ->render();
    }

    public function postBecomeVendor(BecomeVendorRequest $request)
    {
        if (! MarketplaceHelper::isVendorRegistrationEnabled()) {
            abort(404);
        }

        $customer = auth('customer')->user();

        if ($customer->is_vendor) {
            abort(404);
        }

        $existing = SlugHelper::getSlug($request->input('shop_url'), SlugHelper::getPrefix(Store::class));

        if ($existing) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Shop URL is existing. Please choose another one!'));
        }

        event(new Registered($customer));

        return $this
            ->httpResponse()
            ->setNextUrl(route('marketplace.vendor.dashboard'))
            ->setMessage(__('Registered successfully!'));
    }
}
