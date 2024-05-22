<?php

namespace Botble\Ecommerce\Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Tax;

class TaxSeeder extends BaseSeeder
{
    public function run(): void
    {
        Tax::query()->truncate();

        $taxes = [
            [
                'title' => 'VAT',
                'percentage' => 10,
                'priority' => 1,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            [
                'title' => 'None',
                'percentage' => 0,
                'priority' => 2,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            [
                'title' => 'Import Tax',
                'percentage' => 15,
                'priority' => 3,
                'status' => BaseStatusEnum::PUBLISHED,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
        ];

        Tax::query()->insert($taxes);
    }
}
