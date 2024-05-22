<?php

namespace Botble\SocialLogin\Supports;

use Botble\SocialLogin\Facades\SocialService;

class FacebookDataDeletionSignedRequestParser
{
    public function parse(string $signedRequest): ?array
    {
        [$encodedSig, $payload] = explode('.', $signedRequest, 2);

        $secret = SocialService::setting('facebook_app_secret');

        $sign = $this->base64UrlDecode($encodedSig);
        $data = json_decode($this->base64UrlDecode($payload), true);

        $expectedSign = hash_hmac('sha256', $payload, $secret, true);

        if ($sign !== $expectedSign) {
            error_log('Bad Signed JSON signature!');

            return null;
        }

        return $data;
    }

    protected function base64UrlDecode($input): string
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
