<?php

namespace Botble\PayPalPayout\PayPalPayoutsSDK\Core;

/**
 * Class PayPalUserAgent
 * PayPalUserAgent generates User Agent for curl requests
 *
 * @package PayPal\Core
 */
class UserAgent
{
    /**
     * Returns the value of the User-Agent header
     * Add environment values and php version numbers
     *
     * @return string
     */
    public static function getValue()
    {
        $featureList = [
            'platform-ver=' . PHP_VERSION,
            'bit=' . self::getPHPBit(),
            'os=' . str_replace(' ', '_', php_uname('s') . ' ' . php_uname('r')),
            'machine=' . php_uname('m'),
        ];
        if (defined('OPENSSL_VERSION_TEXT')) {
            $opensslVersion = explode(' ', OPENSSL_VERSION_TEXT);
            $featureList[] = 'crypto-lib-ver=' . $opensslVersion[1];
        }
        if (function_exists('curl_version')) {
            $curlVersion = curl_version();
            $featureList[] = 'curl=' . $curlVersion['version'];
        }

        return sprintf('PayPalSDK/%s %s (%s)', 'Checkout-PHP-SDK', Version::VERSION, implode('; ', $featureList));
    }
    /**
     * Gets PHP Bit version
     *
     * @return int|string
     */
    private static function getPHPBit(): int|string
    {
        return match (PHP_INT_SIZE) {
            4 => '32',
            8 => '64',
            default => PHP_INT_SIZE,
        };
    }
}
