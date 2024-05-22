@extends(EcommerceHelper::viewPath('customers.layouts.account-settings'))

@section('title', __('Account information'))

@section('account-content')
    {!! $form->renderForm() !!}

    @if (get_ecommerce_setting('enabled_customer_account_deletion', true))
        <div class="delete-account-section">
            <h2 class="customer-page-title text-danger">{{ __('Delete account') }}</h2>

            <p>
                {{ __('This action will permanently delete your account and all associated data and irreversible. Please be sure before proceeding.') }}
            </p>

            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete-account-modal" data-toggle="modal" data-target="#delete-account-modal">{{ __('Delete your account') }}</button>

            <div class="modal fade" id="delete-account-modal" tabindex="-1" aria-labelledby="delete-account-modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title fs-6">
                                {{ __('Are you sure you want to do this?') }}
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">
                                {{ __('We will send you an email to confirm your account deletion. Once you confirm, your account will be deleted permanently.') }}
                            </p>
                            <x-core::form :url="route('customer.delete-account.store')" method="post">
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('Confirm your password') }}</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label">{{ __('Reason (optional)') }}</label>
                                    <textarea id="reason" name="reason" class="form-control" rows="3"></textarea>
                                </div>
                                <button type="submit" class="w-100 btn btn-danger">{{ __('Request delete account') }}</button>
                            </x-core::form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
