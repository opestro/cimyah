<?php

namespace Botble\SocialLogin\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Media\Facades\RvMedia;
use Botble\SocialLogin\Facades\SocialService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialLoginController extends BaseController
{
    public function redirectToProvider(string $provider, Request $request)
    {
        $this->ensureProviderIsExisted($provider);

        $guard = $this->guard($request);

        if (! $guard) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(BaseHelper::getHomepageUrl());
        }

        $this->setProvider($provider);

        session(['social_login_guard_current' => $guard]);

        return Socialite::driver($provider)->redirect();
    }

    protected function guard(Request $request = null)
    {
        if ($request) {
            $guard = $request->input('guard');
        } else {
            $guard = session('social_login_guard_current');
        }

        if (! $guard) {
            $guard = array_key_first(SocialService::supportedModules());
        }

        if (! $guard || ! SocialService::isSupportedModuleByKey($guard) || Auth::guard($guard)->check()) {
            return false;
        }

        return $guard;
    }

    protected function setProvider(string $provider): bool
    {
        config()->set([
            'services.' . $provider => [
                'client_id' => SocialService::setting($provider . '_app_id'),
                'client_secret' => SocialService::setting($provider . '_app_secret'),
                'redirect' => route('auth.social.callback', $provider),
            ],
        ]);

        return true;
    }

    public function handleProviderCallback(string $provider)
    {
        $this->ensureProviderIsExisted($provider);

        $guard = $this->guard();

        if (! $guard) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl(BaseHelper::getHomepageUrl())
                ->setMessage(__('An error occurred while trying to login'));
        }

        $this->setProvider($provider);

        $providerData = Arr::get(SocialService::supportedModules(), $guard);

        try {
            /**
             * @var AbstractUser $oAuth
             */
            $oAuth = Socialite::driver($provider)->user();
        } catch (Exception $exception) {
            $message = $exception->getMessage();

            if (in_array($provider, ['github', 'facebook'])) {
                $message = json_encode($message);
            }

            if (! $message) {
                $message = __('An error occurred while trying to login');
            }

            if ($exception instanceof InvalidStateException) {
                $message = __('InvalidStateException occurred while trying to login');
            }

            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl($providerData['login_url'])
                ->setMessage($message);
        }

        if (! $oAuth->getEmail()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setNextUrl($providerData['login_url'])
                ->setMessage(__('Cannot login, no email provided!'));
        }

        $model = new $providerData['model']();

        $account = $model->where('email', $oAuth->getEmail())->first();

        if (! $account) {
            $beforeProcessData = apply_filters('social_login_before_creating_account', null, $oAuth, $providerData);

            if ($beforeProcessData instanceof BaseHttpResponse) {
                return $beforeProcessData;
            }

            $avatarId = null;

            try {
                $url = $oAuth->getAvatar();
                if ($url) {
                    $result = RvMedia::uploadFromUrl($url, 0, $model->upload_folder ?: 'accounts', 'image/png');
                    if (! $result['error']) {
                        $avatarId = $result['data']->id;
                    }
                }
            } catch (Exception $exception) {
                BaseHelper::logError($exception);
            }

            $data = [
                'name' => $oAuth->getName() ?: $oAuth->getEmail(),
                'email' => $oAuth->getEmail(),
                'password' => Hash::make(Str::random(36)),
                'avatar_id' => $avatarId,
            ];

            $data = apply_filters('social_login_before_saving_account', $data, $oAuth, $providerData);

            $account = $model;
            $account->fill($data);
            $account->confirmed_at = Carbon::now();
            $account->save();
        }

        Auth::guard($guard)->login($account, true);

        return $this
            ->httpResponse()
            ->setNextUrl($providerData['redirect_url'] ?: BaseHelper::getHomepageUrl())
            ->setMessage(trans('core/acl::auth.login.success'));
    }

    protected function ensureProviderIsExisted(string $provider): void
    {
        abort_if(! in_array($provider, SocialService::getProviderKeys(), true), 404);
    }
}
