<?php

namespace Botble\Ecommerce\Database\Seeders;

use Botble\Ecommerce\Models\GlobalOption;
use Botble\Ecommerce\Models\GlobalOptionValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductOptionSeeder extends Seeder
{
    protected function truncateTables(): void
    {
        DB::table('ec_global_options')->truncate();
        DB::table('ec_global_option_value')->truncate();
        DB::table('ec_options')->truncate();
        DB::table('ec_option_value')->truncate();
    }

    protected function saveGlobalOption(array $options): void
    {
        $this->truncateTables();

        foreach ($options as $option) {
            $globalOption = new GlobalOption();
            $globalOption->name = $option['name'];
            $globalOption->option_type = $option['option_type'];
            $globalOption->required = $option['required'];
            $globalOption->save();
            $optionValue = $this->formatGlobalOptionValue($option['values']);
            $globalOption->values()->saveMany($optionValue);
        }
    }

    protected function formatGlobalOptionValue(array $data): array
    {
        $values = [];
        foreach ($data as $item) {
            $globalOptionValue = new GlobalOptionValue();
            $item['affect_price'] = ! empty($item['affect_price']) ? $item['affect_price'] : 0;
            $globalOptionValue->fill($item);
            $values[] = $globalOptionValue;
        }

        return $values;
    }
}
