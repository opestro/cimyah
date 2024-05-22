<?php

namespace Botble\Ecommerce\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Enums\DeletionRequestStatusEnum;
use Botble\Ecommerce\Http\Requests\Fronts\AccountDeletionRequest;
use Botble\Ecommerce\Jobs\CustomerDeleteAccountJob;
use Botble\Ecommerce\Models\CustomerDeletionRequest;
use Botble\Ecommerce\Notifications\ConfirmDeletionRequestNotification;
use Botble\Theme\Facades\Theme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AccountDeletionController extends BaseController
{
    public function store(AccountDeletionRequest $request)
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $request->user('customer');

        /** @var CustomerDeletionRequest $deletionRequest */
        $deletionRequest = CustomerDeletionRequest::query()->firstOrCreate([
            'customer_id' => $user->getKey(),
        ], [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'token' => Str::random(60),
            'status' => DeletionRequestStatusEnum::WAITING_FOR_CONFIRMATION,
            'reason' => $request->input('reason'),
        ]);

        $user->notify(new ConfirmDeletionRequestNotification($deletionRequest));

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::account-deletion.request_submitted'));
    }

    public function confirm(string $token, Request $request)
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $deletionRequest = CustomerDeletionRequest::query()
            ->where('token', $token)
            ->where('status', DeletionRequestStatusEnum::WAITING_FOR_CONFIRMATION)
            ->firstOrFail();

        if ($deletionRequest->customer()->isNot($request->user('customer'))) {
            abort(403);
        }

        $deletionRequest->update([
            'status' => DeletionRequestStatusEnum::CONFIRMED,
            'confirmed_at' => Carbon::now(),
        ]);

        Auth::guard('customer')->logout();

        CustomerDeleteAccountJob::dispatch($deletionRequest);

        return Theme::scope(
            'ecommerce.customers.delete-account-confirmed',
            default: 'plugins/ecommerce::themes.customers.delete-account-confirmed'
        )->render();
    }
}
