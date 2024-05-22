<?php

namespace Botble\Ecommerce\Supports;

use Botble\Base\Models\BaseQueryBuilder;
use Botble\Ecommerce\Facades\EcommerceHelper as EcommerceHelperFacade;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RenderProductAttributeSetsOnSearchPageSupport
{
    public function __construct(protected Request $request)
    {
    }

    public function getAttributeSets(): Collection
    {
        $with = [
            'categories:id',
            'attributes' => fn (HasMany $query) => $query->whereHas('productVariationItems'),
        ];

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            $with[] = 'attributes.translations';
        }

        return ProductAttributeSet::query()
            ->where('is_searchable', true)
            ->wherePublished()
            ->when($this->request->input('categories', []), function (BaseQueryBuilder $query, $categoryIds) {
                $query->where(function (BaseQueryBuilder $query) use ($categoryIds) {
                    $query
                        ->whereDoesntHave('categories')
                        ->orWhereHas(
                            'categories',
                            fn (BaseQueryBuilder $query) => $query->whereIn('id', $categoryIds)
                        );
                });
            })
            ->orderBy('order')
            ->with($with)
            ->get();
    }

    public function getSelectedAttributes(Collection $attributeSets): array
    {
        $selectedAttrs = [];

        $attributesInput = (array) $this->request->input('attributes', []);

        if (! array_is_list($attributesInput)) {
            foreach ($attributeSets as $attributeSet) {
                $attributeInput = Arr::get($attributesInput, $attributeSet->slug, []);

                if (! is_array($attributeInput)) {
                    continue;
                }

                $selectedAttrs[$attributeSet->slug] = $attributeInput;
            }
        } else {
            $selectedAttrs = $attributesInput;
        }

        return $selectedAttrs;
    }

    public function render(array $params = []): string
    {
        if (! EcommerceHelperFacade::isEnabledFilterProductsByAttributes()) {
            return '';
        }

        $params = ['view' => EcommerceHelperFacade::viewPath('attributes.attributes-filter-renderer'), ...$params];

        $attributeSets = $this->getAttributeSets();
        $selectedAttrs = $this->getSelectedAttributes($attributeSets);

        return view(
            $params['view'],
            array_merge($params, compact('attributeSets', 'selectedAttrs'))
        )->render();
    }
}
