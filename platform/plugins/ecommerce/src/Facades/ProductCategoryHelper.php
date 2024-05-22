<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\ProductCategoryHelper as BaseProductCategoryHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getAllProductCategories(array $params = [], bool $onlyParent = false)
 * @method static \Illuminate\Support\Collection getActiveTreeCategories()
 * @method static \Illuminate\Support\Collection getTreeCategories(bool $activeOnly = false)
 * @method static array getTreeCategoriesOptions(\Illuminate\Support\Collection|array $categories, array $options = [], string|null $indent = null)
 * @method static string renderProductCategoriesSelect(string|int|null $selected = null)
 * @method static \Illuminate\Support\Collection getProductCategoriesWithUrl(array $categoryIds = [], array $condition = [], int|null $limit = null)
 * @method static \Illuminate\Database\Query\Builder applyQuery(\Illuminate\Database\Query\Builder $query)
 *
 * @see \Botble\Ecommerce\Supports\ProductCategoryHelper
 */
class ProductCategoryHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseProductCategoryHelper::class;
    }
}
