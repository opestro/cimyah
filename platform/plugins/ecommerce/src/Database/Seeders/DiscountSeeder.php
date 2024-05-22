<?php

namespace Botble\Ecommerce\Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Enums\DiscountTypeEnum;
use Botble\Ecommerce\Enums\DiscountTypeOptionEnum;
use Botble\Ecommerce\Models\Discount;
use Illuminate\Support\Str;

class DiscountSeeder extends BaseSeeder
{
    public function run(): void
    {
        Discount::query()->truncate();

        $fake = $this->fake();
        $now = $this->now();

        foreach (range(1, 10) as $index) {
            Discount::query()->create([
                'type' => DiscountTypeEnum::COUPON,
                'title' => sprintf('Discount %s', $index),
                'code' => strtoupper(Str::random(12)),
                'start_date' => $now->clone()->subDay(),
                'end_date' => $fake->boolean() ? $now->clone()->addDays($fake->numberBetween(1, 30)) : null,
                'type_option' => $typeOption = $fake->randomElement(array_values(DiscountTypeOptionEnum::toArray())),
                'value' => match ($typeOption) {
                    DiscountTypeOptionEnum::PERCENTAGE => $fake->numberBetween(1, 100),
                    default => $fake->numberBetween(10, 1000),
                },
                'display_at_checkout' => true,
            ]);
        }
    }
}
