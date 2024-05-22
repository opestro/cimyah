<?php

namespace Botble\Ecommerce\Http\Controllers;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Pdf;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Shipment;
use Botble\Media\Facades\RvMedia;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PrintShippingLabelController extends BaseController
{
    public function __invoke(Shipment $shipment, Pdf $pdf): Response
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);

        if ($shipment->tracking_link) {
            $url = $shipment->tracking_link;
        } else {
            $params = EcommerceHelper::isLoginUsingPhone() ? ['phone' => $shipment->order->user->phone] : ['email' => $shipment->order->user->email];
            $url = route('public.orders.tracking', ['order_id' => get_order_code($shipment->order_id), ...$params]);
        }

        $qrCode = $writer->writeString($url);

        return $pdf
            ->templatePath(plugin_path('ecommerce/resources/templates/shipping-label.tpl'))
            ->destinationPath(storage_path('app/templates/ecommerce/shipping-label.tpl'))
            ->paperSizeHalfLetter()
            ->supportArabic()
            ->data(apply_filters('ecommerce_shipping_label_data', [
                'shipment' => [
                    'order_number' => get_order_code($shipment->order_id),
                    'code' => get_shipment_code($shipment->id),
                    'weight' => $shipment->weight,
                    'weight_unit' => ecommerce_weight_unit(),
                    'created_at' => BaseHelper::formatDate($shipment->created_at),
                    'shipping_method' => $shipment->order->shipping_method_name,
                    'shipping_fee' => format_price($shipment->price),
                    'shipping_company_name' => $shipment->shipping_company_name,
                    'tracking_id' => $shipment->tracking_id,
                    'tracking_link' => $shipment->tracking_link,
                    'note' => Str::limit($shipment->note, 90),
                    'qr_code' => base64_encode($qrCode),
                ],
                'sender' => [
                    'logo' => RvMedia::getRealPath(theme_option('logo')),
                    'name' => get_ecommerce_setting('store_name'),
                    'phone' => get_ecommerce_setting('store_phone'),
                    'email' => get_ecommerce_setting('store_email'),
                    'country' => $country = get_ecommerce_setting('store_country'),
                    'state' => $state = get_ecommerce_setting('store_state'),
                    'city' => $city = get_ecommerce_setting('store_city'),
                    'zip_code' => $zipCode = get_ecommerce_setting('store_zip_code'),
                    'address' => $address = get_ecommerce_setting('store_address'),
                    'full_address' => implode(', ', array_filter([
                        $address,
                        $city,
                        $state,
                        $country,
                        EcommerceHelper::isZipCodeEnabled() ? $zipCode : '',
                    ])),
                ],
                'receiver' => [
                    'name' => $shipment->order->user_name,
                    'full_address' => $shipment->order->full_address,
                    'email' => $shipment->order->user->email,
                    'phone' => $shipment->order->user->phone,
                ],
            ], $shipment))
            ->compile()
            ->stream();
    }
}
