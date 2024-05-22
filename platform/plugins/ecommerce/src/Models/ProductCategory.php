<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Contracts\HasTreeCategory as HasTreeCategoryContract;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\Base\Models\BaseModel;
use Botble\Base\Traits\HasTreeCategory;
use Botble\Ecommerce\Tables\ProductTable;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductCategory extends BaseModel implements HasTreeCategoryContract
{
    use HasTreeCategory;

    protected $table = 'ec_product_categories';

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'order',
        'status',
        'image',
        'is_featured',
        'icon',
        'icon_image',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (ProductCategory $category) {
            $category->products()->detach();

            $category->children()->each(fn (ProductCategory $child) => $child->delete());

            $category->brands()->detach();
            $category->productAttributeSets()->detach();
        });
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Product::class,
                'ec_product_category_product',
                'category_id',
                'product_id'
            )
            ->where('is_variation', 0);
    }

    public function parent(): BelongsTo
    {
        return $this
            ->belongsTo(ProductCategory::class, 'parent_id')
            ->whereNot('parent_id', $this->getKey())
            ->withDefault();
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(ProductCategory::class, 'parent_id')
            ->whereNot('id', $this->getKey());
    }

    public function activeChildren(): HasMany
    {
        return $this
            ->children()
            ->wherePublished()
            ->orderBy('order')
            ->with(['slugable', 'activeChildren']);
    }

    public function brands(): MorphToMany
    {
        return $this->morphedByMany(Brand::class, 'reference', 'ec_product_categorizables', 'category_id');
    }

    public function productAttributeSets(): MorphToMany
    {
        return $this->morphedByMany(ProductAttributeSet::class, 'reference', 'ec_product_categorizables', 'category_id');
    }

    protected function parents(): Attribute
    {
        return Attribute::get(function (): Collection {
            $parents = collect();

            $parent = $this->parent;

            while ($parent->id) {
                $parents->push($parent);
                $parent = $parent->parent;
            }

            return $parents;
        });
    }

    protected function badgeWithCount(): Attribute
    {
        return Attribute::get(function (): HtmlString {
            $link = route('products.index', [
                'filter_table_id' => strtolower(Str::slug(Str::snake(ProductTable::class))),
                'class' => Product::class,
                'filter_columns' => ['category'],
                'filter_operators' => ['='],
                'filter_values' => [$this->id],
            ]);

            return Html::link($link, sprintf('(%s)', $this->products_count), [
                'data-bs-toggle' => 'tooltip',
                'data-bs-original-title' => trans('plugins/ecommerce::product-categories.total_products', ['total' => $this->products_count]),
            ]);
        });
    }

    protected function iconHtml(): Attribute
    {
        return Attribute::get(function (): ?HtmlString {
            if ($this->icon_image) {
                return RvMedia::image($this->icon_image, attributes: ['alt' => $this->name]);
            }

            if ($this->icon) {
                return Html::tag('i', '', $this->icon);
            }

            return null;
        });
    }
}
