@if (SocialService::hasAnyProviderEnable())
    <div class="login-options">

        <div class="login-options-title">
            <p>{{ __('Login with social networks') }}</p>
        </div>

        <ul class="social-icons">
            @foreach (SocialService::getProviderKeys() as $item)
                @if (SocialService::getProviderEnabled($item))
                    {!! apply_filters('social_login_' . $item . '_render', sprintf('
                            <li>
                                <a
                                    class="%s"
                                    data-bs-toggle="tooltip"
                                    data-bs-original-title="%s"
                                    href="%s"
                                ></a>
                            </li>
                    ', $item, $item, route('auth.social', isset($params) ? array_merge([$item], $params) : $item)), $item) !!}
                @endif
            @endforeach
        </ul>
    </div>
@endif
