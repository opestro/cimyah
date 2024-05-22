<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Ecommerce\Forms\CustomerForm;
use Botble\Ecommerce\Http\Controllers\BaseController;
use Botble\Ecommerce\Http\Requests\AddCustomerWhenCreateOrderRequest;
use Botble\Ecommerce\Http\Requests\CustomerCreateRequest;
use Botble\Ecommerce\Http\Requests\CustomerEditRequest;
use Botble\Ecommerce\Http\Requests\CustomerUpdateEmailRequest;
use Botble\Ecommerce\Http\Resources\CustomerAddressResource;
use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Tables\CustomerReviewTable;
use Botble\Ecommerce\Tables\CustomerTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/ecommerce::customer.name'), route('customers.index'));
    }

    public function index(CustomerTable $dataTable)
    {
        $this->pageTitle(trans('plugins/ecommerce::customer.name'));

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/ecommerce::customer.create'));

        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/customer.js');

        return CustomerForm::create()->remove('is_change_password')->renderForm();
    }

    public function store(CustomerCreateRequest $request)
    {
        $customer = new Customer();
        $customer->fill($request->input());
        $customer->confirmed_at = Carbon::now();
        $customer->password = Hash::make($request->input('password'));
        $customer->dob = Carbon::parse($request->input('dob'));
        $customer->save();

        event(new CreatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customers.index'))
            ->setNextUrl(route('customers.edit', $customer->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Customer $customer)
    {
        Assets::addScriptsDirectly('vendor/core/plugins/ecommerce/js/customer.js');

        $this->pageTitle(trans('plugins/ecommerce::customer.edit', ['name' => $customer->name]));

        $customer->password = null;

        return CustomerForm::createFromModel($customer)->renderForm();
    }

    public function update(Customer $customer, CustomerEditRequest $request)
    {
        $customer->fill($request->except('password'));

        if ($request->input('is_change_password') == 1) {
            $customer->password = Hash::make($request->input('password'));
        }

        $customer->dob = Carbon::parse($request->input('dob'));

        $customer->save();

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customers.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Customer $customer)
    {
        return DeleteResourceAction::make($customer);
    }

    public function verifyEmail(int|string $id, Request $request)
    {
        $customer = Customer::query()
            ->where([
                'id' => $id,
                'confirmed_at' => null,
            ])->firstOrFail();

        $customer->confirmed_at = Carbon::now();
        $customer->save();

        event(new UpdatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('customers.index'))
            ->withUpdatedSuccessMessage();
    }

    public function getListCustomerForSelect()
    {
        $customers = Customer::query()
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        return $this
            ->httpResponse()
            ->setData($customers);
    }

    public function getListCustomerForSearch(Request $request)
    {
        $customers = Customer::query()
            ->where('name', 'LIKE', '%' . $request->input('keyword') . '%')
            ->simplePaginate(5);

        foreach ($customers as &$customer) {
            $customer->avatar_url = (string) $customer->avatar_url;
        }

        return $this
            ->httpResponse()->setData($customers);
    }

    public function postUpdateEmail($id, CustomerUpdateEmailRequest $request)
    {
        $customer = Customer::query()->findOrFail($id);

        $customer->email = $request->input('email');
        $customer->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function getCustomerAddresses($id)
    {
        $addresses = Address::query()->where('customer_id', $id)->get();

        return $this
            ->httpResponse()
            ->setData(CustomerAddressResource::collection($addresses));
    }

    public function getCustomerOrderNumbers($id)
    {
        $customer = Customer::query()->find($id);

        if (! $customer) {
            return $this
                ->httpResponse()
                ->setData(0);
        }

        return $this
            ->httpResponse()
            ->setData($customer->orders()->count());
    }

    public function postCreateCustomerWhenCreatingOrder(AddCustomerWhenCreateOrderRequest $request)
    {
        $request->merge(['password' => Hash::make(Str::random(36))]);
        $customer = Customer::query()->create($request->input());
        $customer->avatar = (string) $customer->avatar_url;

        event(new CreatedContentEvent(CUSTOMER_MODULE_SCREEN_NAME, $request, $customer));

        $request->merge([
            'customer_id' => $customer->id,
            'is_default' => true,
        ]);

        $address = Address::query()->create($request->input());

        $address->country = $address->country_name;
        $address->state = $address->state_name;
        $address->city = $address->city_name;

        return $this
            ->httpResponse()
            ->setData(compact('address', 'customer'))
            ->withCreatedSuccessMessage();
    }

    public function ajaxReviews(int|string $id, CustomerReviewTable $customerReviewTable)
    {
        return $customerReviewTable->customerId($id)->renderTable();
    }
}
