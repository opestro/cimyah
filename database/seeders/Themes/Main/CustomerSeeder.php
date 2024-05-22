<?php

namespace Database\Seeders\Themes\Main;

use Botble\Ecommerce\Models\Address;
use Botble\Ecommerce\Models\Customer;
use Botble\Theme\Database\Seeders\ThemeSeeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends ThemeSeeder
{
    public function run(): void
    {
        $this->uploadFiles('customers');

        Customer::query()->truncate();
        Address::query()->truncate();

        $fake = $this->fake();

        $customers = [
            'customer@botble.com',
            'vendor@botble.com',
        ];

        $now = Carbon::now();

        foreach ($customers as $item) {
            $customer = Customer::query()->forceCreate([
                'name' => $fake->name(),
                'email' => $item,
                'password' => Hash::make('12345678'),
                'phone' => $fake->e164PhoneNumber(),
                'avatar' => $this->filePath(sprintf('customers/%s.jpg', $fake->numberBetween(1, 10))),
                'dob' => Carbon::now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
                'confirmed_at' => $now,
            ]);

            Address::query()->create([
                'name' => $customer->name,
                'phone' => $fake->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $fake->countryCode(),
                'state' => $fake->state(),
                'city' => $fake->city(),
                'address' => $fake->streetAddress(),
                'zip_code' => $fake->postcode(),
                'customer_id' => $customer->getKey(),
                'is_default' => true,
            ]);

            Address::query()->create([
                'name' => $customer->name,
                'phone' => $fake->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $fake->countryCode(),
                'state' => $fake->state(),
                'city' => $fake->city(),
                'address' => $fake->streetAddress(),
                'zip_code' => $fake->postcode(),
                'customer_id' => $customer->getKey(),
                'is_default' => false,
            ]);
        }

        for ($i = 0; $i < 8; $i++) {
            $customer = Customer::query()->forceCreate([
                'name' => $fake->name(),
                'email' => $fake->unique()->safeEmail(),
                'password' => Hash::make('12345678'),
                'phone' => $fake->e164PhoneNumber(),
                'avatar' => $this->filePath(sprintf('customers/%d.jpg', $i + 1)),
                'dob' => Carbon::now()->subYears(rand(20, 50))->subDays(rand(1, 30)),
                'confirmed_at' => $now,
            ]);

            Address::query()->create([
                'name' => $customer->name,
                'phone' => $fake->e164PhoneNumber(),
                'email' => $customer->email,
                'country' => $fake->countryCode(),
                'state' => $fake->state(),
                'city' => $fake->city(),
                'address' => $fake->streetAddress(),
                'zip_code' => $fake->postcode(),
                'customer_id' => $customer->getKey(),
                'is_default' => true,
            ]);
        }
    }
}
