<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Database\Seeders\Traits\HasProductSeeder;
use Botble\Marketplace\Models\Store;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends ThemeSeeder
{
    use HasProductSeeder;

    public function run(): void
    {
        $this->uploadFiles('products');

        $fake = $this->fake();

        $content = File::get(database_path("seeders/contents/product-content-{$this->getThemeName()}.html"));

        $images = $this->getFilesFromPath('products')
            ->map(fn ($item) => $this->filePath($item))
            ->all();

        $storeIds = Store::query()->pluck('id');

        $this->createProducts(array_map(function ($item) use ($fake, $images, $content, $storeIds) {
            return [
                'name' => $item,
                'description' => $fake->randomElement($this->getDescriptions()),
                'content' => $content,
                'images' => $fake->randomElements($images, rand(3, 8)),
                'store_id' => $fake->randomElement($storeIds),
            ];
        }, $this->getProducts()));
    }

    protected function getProducts(): array
    {
        return [
            'EcoTech Marine Radion XR30w G5 Pro LED Light Fixture',
            'Philips Hue White and Color Ambiance A19 LED Smart Bulb',
            'Samsung Galaxy Tab S7+ 12.4-Inch Android Tablet',
            'Apple MacBook Pro 16-Inch Laptop',
            'Sony WH-1000XM4 Wireless Noise-Canceling Headphones',
            'DJI Mavic Air 2 Drone',
            'GoPro HERO9 Black Action Camera',
            'Bose SoundLink Revolve+ Portable Bluetooth Speaker',
            'Nest Learning Thermostat (3rd Generation)',
            'Ring Video Doorbell Pro',
            'Amazon Echo Show 10 (3rd Gen)',
            'Samsung QN90A Neo QLED 4K Smart TV',
            'LG OLED C1 Series 4K Smart TV',
            'Sony X950H 4K Ultra HD Smart LED TV',
            'Apple Watch Series 7',
            'Fitbit Charge 5 Fitness Tracker',
            'Garmin Fenix 7X Sapphire Solar GPS Watch',
            'Microsoft Surface Pro 8',
            'Lenovo ThinkPad X1 Carbon Gen 9 Laptop',
            'HP Spectre x360 14-Inch Convertible Laptop',
            'Razer Blade 15 Advanced Gaming Laptop',
            'Alienware m15 R6 Gaming Laptop',
            'Corsair K95 RGB Platinum XT Mechanical Gaming Keyboard',
            'Logitech G Pro X Superlight Wireless Gaming Mouse',
            'SteelSeries Arctis Pro Wireless Gaming Headset',
            'Elgato Stream Deck XL',
            'Nintendo Switch OLED Model',
            'PlayStation 5 Console',
            'Xbox Series X Console',
            'Oculus Quest 2 VR Headset',
            'HTC Vive Cosmos Elite VR Headset',
            'Samsung Odyssey G9 49-Inch Curved Gaming Monitor',
            'LG UltraGear 27GN950-B 4K Gaming Monitor',
            'Acer Predator X38 Pbmiphzx 38-Inch Curved Gaming Monitor',
            'ASUS ROG Swift PG279QM 27-Inch Gaming Monitor',
            'BenQ EW3280U 32-Inch 4K HDR Entertainment Monitor',
            'Dell UltraSharp U2720Q 27-Inch 4K USB-C Monitor',
            'HP Z27k G3 4K USB-C Monitor',
            'LG 27UK850-W 27-Inch 4K UHD IPS Monitor',
            'Samsung Odyssey G7 32-Inch Curved Gaming Monitor',
            'Sony X900H 4K Ultra HD Smart LED TV',
            'TCL 6-Series 4K UHD Dolby Vision HDR QLED Roku Smart TV',
            'Vizio OLED65-H1 65-Inch 4K OLED Smart TV',
            'Hisense U8G Quantum Series 4K ULED Android TV',
            'LG C1 Series 4K OLED Smart TV',
            'Samsung QN85A Neo QLED 4K Smart TV',
            'Sony A90J 4K OLED Smart TV',
            'Apple TV 4K (2nd Generation)',
            'Roku Ultra 2020 Streaming Media Player',
            'Amazon Fire TV Stick 4K Max',
            'Google Chromecast with Google TV',
            'NVIDIA SHIELD TV Pro',
            'Sonos Beam Gen 2 Soundbar',
            'Bose Smart Soundbar 900',
            'JBL Bar 9.1 Soundbar with Dolby Atmos',
            'Sennheiser Ambeo Soundbar',
            'Sony HT-A9 Home Theater System',
        ];
    }

    protected function getDescriptions(): array
    {
        return [
            'Jabra Evolve2 75 USB-A MS Teams Stereo Headset The Jabra Evolve2 75 USB-A MS Teams Stereo Headset has replaced previous hybrid working standards. Industry-leading call quality thanks to top-notch audio engineering.',
            'With this intelligent headset, you can stay connected and productive from the first call of the day to the last train home. With an ergonomic earcup design, this headset invented a brand-new dual-foam technology. You will be comfortable from the first call to the last thanks to the re-engineered leatherette ear cushion design that allows for better airflow.',
            'We can provide exceptional noise isolation and the best all-day comfort by mixing firm foam for the outer with soft foam for the interior of the ear cushions. So that you may receive Active Noise-Cancellation (ANC) performance that is even greater in a headset that you can wear for whatever length you wish.',
            'The headset also offers MS Teams Certifications and other features like Busylight, Calls controls, Voice guiding, and Wireless range (ft): Up to 100 feet. Best-in-class. Boom The most recent Jabra Evolve2 75 USB-A MS Teams Stereo Headset offers professional-grade call performance that leads the industry, yet Evolve2 75 wins best-in-class.',
            'Additionally, this includes a redesigned microphone boom arm that is 33 percent shorter than the Evolve 75 and offers the industry-leading call performance for which Jabra headsets are known.',
            'It complies with Microsoft\'s Open Office criteria and is specially tuned for outstanding conversations in open-plan workplaces and other loud environments when the microphone boom arm is lowered in Performance Mode',
        ];
    }
}
