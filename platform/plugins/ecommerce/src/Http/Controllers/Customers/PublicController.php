<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\OrderReturnHelper;
use Botble\Ecommerce\Forms\Fronts\Auth\ChangePasswordForm;
use Botble\Ecommerce\Forms\Fronts\Customer\AddressForm;
use Botble\Ecommerce\Forms\Fronts\Customer\CustomerForm;
use Botble\Ecommerce\Http\Requests\AddressRequest;
use Botble\Ecommerce\Http\Requests\AvatarRequest;
use Botble\Ecommerce\Http\Requests\EditAccountRequest;
use Botble\Ecommerce\Http\Requests\OrderReturnRequest;
use Botble\Ecommerce\Http\Requests\UpdatePasswordRequest;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\OrderReturn;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Media\Facades\RvMedia;
use Botble\Media\Supports\Zipper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicController extends BaseController
{
    public function __construct()
    {
        Theme::asset()
            ->add('customer-style', 'vendor/core/plugins/ecommerce/css/customer.css', ['bootstrap-css']);

        Theme::asset()
            ->container('footer')
            ->add('ecommerce-utilities-js', 'vendor/core/plugins/ecommerce/js/utilities.js', ['jquery'])
            ->add('cropper-js', 'vendor/core/plugins/ecommerce/libraries/cropper.js', ['jquery'])
            ->add('avatar-js', 'vendor/core/plugins/ecommerce/js/avatar.js', ['jquery']);

        if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation()) {
            Theme::asset()
                ->container('footer')
                ->add('location-js', 'vendor/core/plugins/location/js/location.js', ['jquery']);
        }
    }

    public function getOverview()
    {
        SeoHelper::setTitle(__('Account information'));

        Theme::breadcrumb()
            ->add(__('Account information'), route('customer.overview'));

        $customer = auth('customer')->user();

        return Theme::scope(
            'ecommerce.customers.overview',
            compact('customer'),
            'plugins/ecommerce::themes.customers.overview'
        )
            ->render();
    }

    public function getEditAccount()
    {
        SeoHelper::setTitle(__('Profile'));

        Theme::asset()
            ->add(
                'datepicker-style',
                'vendor/core/core/base/libraries/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                ['bootstrap']
            );
        Theme::asset()
            ->container('footer')
            ->add(
                'datepicker-js',
                'vendor/core/core/base/libraries/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
                ['jquery']
            );

        Theme::breadcrumb()
            ->add(__('Profile'), route('customer.edit-account'));

        $customer = auth('customer')->user();

        $form = CustomerForm::createFromModel($customer);

        return Theme::scope(
            'ecommerce.customers.edit-account',
            compact('form'),
            'plugins/ecommerce::themes.customers.edit-account'
        )
            ->render();
    }

    public function postEditAccount(EditAccountRequest $request)
    {
        /**
         * @var Customer $customer
         */
        $customer = auth('customer')->user();

        CustomerForm::createFromModel($customer)
            ->setRequest($request)
            ->saving(function (CustomerForm $form) {
                $model = $form->getModel();
                $request = $form->getRequest();

                $model->fill($request->except(['email']));

                $model->dob = Carbon::createFromFormat(BaseHelper::getDateFormat(), $request->input('dob'));

                $model->save();

                do_action(HANDLE_CUSTOMER_UPDATED_ECOMMERCE, $model, $request);
            });

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.edit-account'))
            ->setMessage(__('Update profile successfully!'));
    }

    public function getChangePassword()
    {
        SeoHelper::setTitle(__('Change Password'));

        Theme::breadcrumb()
            ->add(__('Change Password'), route('customer.change-password'));

        $form = ChangePasswordForm::create();

        return Theme::scope(
            'ecommerce.customers.change-password',
            compact('form'),
            'plugins/ecommerce::themes.customers.change-password'
        )->render();
    }

    public function postChangePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::guard('customer')->user();

        ChangePasswordForm::createFromModel($user)
            ->setRequest($request)
            ->saving(function (ChangePasswordForm $form) {
                $model = $form->getModel();
                $request = $form->getRequest();

                $model->update([
                    'password' => Hash::make($request->input('password')),
                ]);
            });

        return $this
            ->httpResponse()
            ->setMessage(trans('core/acl::users.password_update_success'));
    }

    public function getListAddresses()
    {
        SeoHelper::setTitle(__('Address books'));

        $addresses = Address::query()
            ->where('customer_id', auth('customer')->id())
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Address books'), route('customer.address'));

        return Theme::scope(
            'ecommerce.customers.address.list',
            compact('addresses'),
            'plugins/ecommerce::themes.customers.address.list'
        )->render();
    }

    public function getCreateAddress()
    {
        SeoHelper::setTitle(__('Create Address'));

        Theme::breadcrumb()
            ->add(__('Address books'), route('customer.address'))
            ->add(__('Create Address'), route('customer.address.create'));

        $form = AddressForm::create();

        return Theme::scope(
            'ecommerce.customers.address.create',
            compact('form'),
            'plugins/ecommerce::themes.customers.address.create'
        )->render();
    }

    public function postCreateAddress(AddressRequest $request)
    {
        $form = AddressForm::create();

        $form->setRequest($request)->saving(function (AddressForm $form) {
            $model = $form->getModel();
            $request = $form->getRequest();

            if ($request->input('is_default') == 1) {
                Address::query()
                    ->where([
                        'is_default' => 1,
                        'customer_id' => auth('customer')->id(),
                    ])
                    ->update(['is_default' => 0]);
            }

            $request->merge([
                'customer_id' => auth('customer')->id(),
                'is_default' => $request->input('is_default', 0),
            ]);

            $model->fill($request->input());
            $model->save();
        });

        $address = $form->getModel();

        return $this
            ->httpResponse()
            ->setData([
                'id' => $address->getKey(),
                'html' => view(
                    'plugins/ecommerce::orders.partials.address-item',
                    compact('address')
                )->render(),
            ])
            ->setNextUrl(route('customer.address'))
            ->withCreatedSuccessMessage();
    }

    public function getEditAddress(int|string $id)
    {
        SeoHelper::setTitle(__('Edit Address #:id', compact('id')));

        $address = Address::query()
            ->where([
                'id' => $id,
                'customer_id' => auth('customer')->id(),
            ])
            ->firstOrFail();

        Theme::breadcrumb()
            ->add(__('Edit Address #:id', compact('id')), route('customer.address.edit', $id));

        $form = AddressForm::createFromModel($address);

        return Theme::scope(
            'ecommerce.customers.address.edit',
            compact('form', 'address'),
            'plugins/ecommerce::themes.customers.address.edit'
        )->render();
    }

    public function getDeleteAddress(int|string $id)
    {
        Address::query()
            ->where([
                'id' => $id,
                'customer_id' => auth('customer')->id(),
            ])
            ->delete();

        return $this
            ->httpResponse()
            ->setNextUrl(route('customer.address'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function postEditAddress(int|string $id, AddressRequest $request)
    {
        $address = Address::query()
            ->where([
                'id' => $id,
                'customer_id' => auth('customer')->id(),
            ])
            ->firstOrFail();

        $form = AddressForm::createFromModel($address)->setRequest($request);

        $form->saving(function (AddressForm $form) {
            $model = $form->getModel();
            $request = $form->getRequest();

            if ($request->input('is_default') == 1) {
                Address::query()
                    ->where([
                        'is_default' => 1,
                        'customer_id' => auth('customer')->id(),
                    ])
                    ->update(['is_default' => 0]);

                $model->is_default = 1;
            }

            $request->merge([
                'is_default' => $request->input('is_default', 0),
            ]);

            $model->fill($request->input());
            $model->save();
        });

        $address = $form->getModel();

        return $this
            ->httpResponse()
            ->setData([
                'id' => $address->getKey(),
                'html' => view('plugins/ecommerce::orders.partials.address-item', compact('address'))
                    ->render(),
            ])
            ->withUpdatedSuccessMessage();
    }

    public function postAvatar(AvatarRequest $request)
    {
        try {
            $account = auth('customer')->user();

            $result = RvMedia::handleUpload($request->file('avatar_file'), 0, $account->upload_folder);

            if ($result['error']) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->setMessage($result['message']);
            }

            $file = $result['data'];

            $account->avatar = $file->url;
            $account->save();

            return $this
                ->httpResponse()
                ->setMessage(__('Updated avatar successfully!'))
                ->setData(['url' => RvMedia::url($file->url)]);
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function getReturnOrder(int|string $orderId)
    {
        if (! EcommerceHelper::isOrderReturnEnabled()) {
            abort(404);
        }

        $order = Order::query()
            ->where([
                'id' => $orderId,
                'user_id' => auth('customer')->id(),
                'status' => OrderStatusEnum::COMPLETED,
            ])
            ->with('products')
            ->firstOrFail();

        if (! $order->canBeReturned()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Request Return Product(s) In Order :id', ['id' => $order->code]));

        Theme::breadcrumb()
            ->add(
                __('Request Return Product(s) In Order :id', ['id' => $order->code]),
                route('customer.order_returns.request_view', $orderId)
            );

        Theme::asset()->container('footer')->add(
            'order-return-js',
            'vendor/core/plugins/ecommerce/js/order-return.js',
            ['jquery']
        );
        Theme::asset()->add('order-return-css', 'vendor/core/plugins/ecommerce/css/order-return.css');

        return Theme::scope(
            'ecommerce.customers.order-returns.view',
            compact('order'),
            'plugins/ecommerce::themes.customers.order-returns.view'
        )->render();
    }

    public function postReturnOrder(OrderReturnRequest $request)
    {
        if (! EcommerceHelper::isOrderReturnEnabled()) {
            abort(404);
        }

        $order = Order::query()
            ->where([
                'id' => $request->input('order_id'),
                'user_id' => auth('customer')->id(),
            ])
            ->firstOrFail();

        if (! $order->canBeReturned()) {
            return $this
                ->httpResponse()
                ->setError()
                ->withInput()
                ->setMessage(trans('plugins/ecommerce::order.return_error'));
        }

        if ($reason = $request->input('reason')) {
            $orderReturnData['reason'] = $reason;
        }

        $orderReturnData['items'] = Arr::where(
            $request->input('return_items'),
            fn ($value) => isset($value['is_return'])
        );

        if (empty($orderReturnData['items'])) {
            return $this
                ->httpResponse()
                ->setError()
                ->withInput()
                ->setMessage(__('Please select at least 1 product to return!'));
        }

        $totalRefundAmount = $order->amount - $order->shipping_amount;
        $totalPriceProducts = $order->products->sum(function ($item) {
            return $item->total_price_with_tax;
        });
        $ratio = $totalRefundAmount <= 0 ? 0 : $totalPriceProducts / $totalRefundAmount;

        foreach ($orderReturnData['items'] as &$item) {
            $orderProductId = Arr::get($item, 'order_item_id');
            if (! $orderProduct = $order->products->firstWhere('id', $orderProductId)) {
                return $this
                    ->httpResponse()
                    ->setError()
                    ->withInput()
                    ->setMessage(__('Oops! Something Went Wrong.'));
            }
            $qty = $orderProduct->qty;
            if (EcommerceHelper::allowPartialReturn()) {
                $qty = (int) Arr::get($item, 'qty') ?: $qty;
                $qty = min($qty, $orderProduct->qty);
            }
            $item['qty'] = $qty;
            $item['refund_amount'] = $ratio == 0 ? 0 : ($orderProduct->price_with_tax * $qty / $ratio);
        }

        [$status, $data, $message] = OrderReturnHelper::returnOrder($order, $orderReturnData);

        if (! $status) {
            return $this
                ->httpResponse()
                ->setError()
                ->withInput()
                ->setMessage($message ?: trans('plugins/ecommerce::order.return_error'));
        }

        OrderHistory::query()->create([
            'action' => OrderHistoryActionEnum::RETURN_ORDER,
            'description' => __(':customer has requested return product(s)', ['customer' => $order->address->name]),
            'order_id' => $order->getKey(),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::order.return_success'))
            ->setNextUrl(route('customer.order_returns.detail', ['id' => $data->id]));
    }

    public function getListReturnOrders()
    {
        if (! EcommerceHelper::isOrderReturnEnabled()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Order Return Requests'));

        $requests = OrderReturn::query()
            ->where('user_id', auth('customer')->id())
            ->orderByDesc('created_at')
            ->withCount('items')
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Order Return Requests'), route('customer.order_returns'));

        return Theme::scope(
            'ecommerce.customers.order-returns.list',
            compact('requests'),
            'plugins/ecommerce::themes.customers.order-returns.list'
        )->render();
    }

    public function getDetailReturnOrder(int|string $id)
    {
        if (! EcommerceHelper::isOrderReturnEnabled()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Order Return Requests'));

        $orderReturn = OrderReturn::query()
            ->where([
                'id' => $id,
                'user_id' => auth('customer')->id(),
            ])
            ->with('latestHistory')
            ->firstOrFail();

        Theme::breadcrumb()
            ->add(__('Order Return Requests'), route('customer.order_returns'))
            ->add(
                __('Order Return Requests :id', ['id' => $orderReturn->id]),
                route('customer.order_returns.detail', $orderReturn->id)
            );

        return Theme::scope(
            'ecommerce.customers.order-returns.detail',
            compact('orderReturn'),
            'plugins/ecommerce::themes.customers.order-returns.detail'
        )->render();
    }

    public function getDownloads()
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Downloads'));

        $orderProducts = OrderProduct::query()
            ->whereHas('order', function (Builder $query) {
                $query
                    ->where('user_id', auth('customer')->id())
                    ->where('is_finished', 1)
                    ->when(is_plugin_active('payment'), function (Builder $query) {
                        $query
                            ->where(function (Builder $query) {
                                $query
                                    ->where('amount', 0)
                                    ->orWhereHas('payment', function ($query) {
                                        $query->where('status', PaymentStatusEnum::COMPLETED);
                                    });
                            });
                    });
            })
            ->where('product_type', ProductTypeEnum::DIGITAL)
            ->orderByDesc('created_at')
            ->with(['order', 'product', 'productFiles', 'product.productFiles'])
            ->paginate(10);

        Theme::breadcrumb()
            ->add(__('Downloads'), route('customer.downloads'));

        return Theme::scope(
            'ecommerce.customers.orders.downloads',
            compact('orderProducts'),
            'plugins/ecommerce::themes.customers.orders.downloads'
        )->render();
    }

    public function getDownload(int|string $id, Request $request)
    {
        if (! EcommerceHelper::isEnabledSupportDigitalProducts()) {
            abort(404);
        }

        $orderProduct = OrderProduct::query()
            ->where([
                'id' => $id,
                'product_type' => ProductTypeEnum::DIGITAL,
            ])
            ->whereHas('order', function (Builder $query) {
                $query
                    ->when(
                        auth('customer')->id(),
                        fn (Builder $query, $customerId) => $query->where('user_id', $customerId)
                    )
                    ->where('is_finished', 1)
                    ->when(is_plugin_active('payment'), function (Builder $query) {
                        $query
                            ->where(function (Builder $query) {
                                $query
                                    ->where('amount', 0)
                                    ->orWhereHas('payment', function ($query) {
                                        $query->where('status', PaymentStatusEnum::COMPLETED);
                                    });
                            });
                    });
            })
            ->with(['order', 'product'])
            ->first();

        if (! $orderProduct) {
            abort(404);
        }

        $order = $orderProduct->order;

        if (auth('customer')->check()) {
            if ($order->user_id != auth('customer')->id()) {
                abort(404);
            }
        } elseif ($hash = $request->input('hash')) {
            $this
                ->httpResponse()
                ->setNextUrl(BaseHelper::getHomepageUrl());
            if (! $orderProduct->download_token || ! Hash::check($orderProduct->download_token, $hash)) {
                abort(404);
            }
        } else {
            abort(404);
        }

        $product = $orderProduct->product;
        $productFiles = $product->id ? $product->productFiles : $orderProduct->productFiles;

        if ($productFiles->isEmpty()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cannot found files'));
        }

        $externalProductFiles = $productFiles->filter(fn ($productFile) => $productFile->is_external_link);

        if ($request->input('external')) {
            if ($externalProductFiles->count()) {
                $orderProduct->increment('times_downloaded');
                if ($externalProductFiles->count() == 1) {
                    $productFile = $externalProductFiles->first();

                    return redirect($productFile->url);
                }

                return Theme::scope(
                    'ecommerce.download-external-links',
                    compact('orderProduct', 'product', 'externalProductFiles'),
                    'plugins/ecommerce::themes.download-external-links'
                )
                    ->render();
            }

            return $this
                ->httpResponse()
                ->setError()->setMessage(__('Cannot download files'));
        }

        $internalProductFiles = $productFiles->filter(fn ($productFile) => ! $productFile->is_external_link);
        if ($internalProductFiles->isEmpty()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('Cannot download files'));
        }

        $zipName = Str::slug($orderProduct->product_name) . Str::random(5) . '-' . Carbon::now()->format(
            'Y-m-d-h-i-s'
        ) . '.zip';

        $storageDisk = Storage::disk('local');

        $fileName = $storageDisk->path($zipName);

        $zip = new Zipper();
        $zip->make($fileName);

        foreach ($internalProductFiles as $file) {
            if (Str::startsWith($file->url, Product::getDigitalProductFilesDirectory())) {
                $filePath = $storageDisk->path($file->url);

                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }

                continue;
            }

            $filePath = RvMedia::getRealPath($file->url);
            if (! RvMedia::isUsingCloud()) {
                if (File::exists($filePath)) {
                    $zip->add($filePath);
                }
            } else {
                $zip->addString(
                    $file->base_name,
                    file_get_contents(str_replace('https://', 'http://', $filePath))
                );
            }
        }

        if (version_compare(phpversion(), '8.0') >= 0) {
            $zip = null;
        } else {
            $zip->close();
        }

        if (File::exists($fileName)) {
            $orderProduct->increment('times_downloaded');

            return response()->download($fileName)->deleteFileAfterSend();
        }

        return $this
            ->httpResponse()
            ->setError()
            ->setMessage(__('Cannot download files'));
    }

    public function getProductReviews(ProductInterface $productRepository)
    {
        if (! EcommerceHelper::isReviewEnabled()) {
            abort(404);
        }

        SeoHelper::setTitle(__('Product Reviews'));

        Theme::asset()
            ->add('ecommerce-review-css', 'vendor/core/plugins/ecommerce/css/review.css');
        Theme::asset()->container('footer')
            ->add('ecommerce-review-js', 'vendor/core/plugins/ecommerce/js/review.js', ['jquery']);

        $customerId = auth('customer')->id();

        $reviews = Review::query()
            ->where('customer_id', $customerId)
            ->whereHas('product', function ($query) {
                $query->wherePublished();
            })
            ->with(['product', 'product.slugable'])
            ->orderByDesc('ec_reviews.created_at')
            ->paginate(12);

        $products = $productRepository->productsNeedToReviewByCustomer($customerId);

        Theme::breadcrumb()
            ->add(__('Product Reviews'), route('customer.product-reviews'));

        return Theme::scope(
            'ecommerce.customers.product-reviews.list',
            compact('products', 'reviews'),
            'plugins/ecommerce::themes.customers.product-reviews.list'
        )->render();
    }
}
