<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('ec_product_categories', 'icon_image')) {
            return;
        }

        Schema::table('ec_product_categories', function (Blueprint $table) {
            $table->string('icon')->nullable();
            $table->string('icon_image')->nullable();
        });

        try {
            $categories = ProductCategory::query()
                ->toBase()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->select([
                    'ec_product_categories.id',
                    'mb1.meta_value as icon_meta',
                    'mb2.meta_value as icon_image_meta',
                ])
                ->leftJoin('meta_boxes as mb1', function (JoinClause $join) {
                    $join
                        ->on('mb1.reference_id', 'ec_product_categories.id')
                        ->where('mb1.reference_type', ProductCategory::class)
                        ->where('mb1.meta_key', 'icon');
                })
                ->leftJoin('meta_boxes as mb2', function (JoinClause $join) {
                    $join
                        ->on('mb2.reference_id', 'ec_product_categories.id')
                        ->where('mb2.reference_type', ProductCategory::class)
                        ->where('mb2.meta_key', 'icon_image');
                })
                ->where(function ($query) {
                    $query->whereNotNull('mb1.meta_value')
                        ->orWhereNotNull('mb2.meta_value');
                })
                ->get();

            foreach ($categories as $category) {
                $icon = $category->icon_meta ? Arr::first(json_decode($category->icon_meta, true)) : null;

                $iconImage = $category->icon_image_meta ? Arr::first(
                    json_decode($category->icon_image_meta, true)
                ) : null;

                ProductCategory::query()
                    ->toBase()
                    ->where('id', $category->id)
                    ->update([
                        'icon' => $icon,
                        'icon_image' => $iconImage,
                    ]);
            }
        } catch (Throwable) {
        }
    }

    public function down(): void
    {
        try {
            Schema::table('ec_product_categories', function (Blueprint $table) {
                $table->dropColumn(['icon', 'icon_image']);
            });
        } catch (Throwable) {
        }
    }
};
