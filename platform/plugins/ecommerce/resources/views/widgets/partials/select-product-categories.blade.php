<div data-bb-toggle="tree-checkboxes">
    <ul class="list-unstyled">
        @foreach (ProductCategoryHelper::getActiveTreeCategories() as $category)
            <li>
                <label class="form-check">
                    <input
                        name="categories[]"
                        type="checkbox"
                        class="form-check-input"
                        value="{{ $category->id }}"
                        @if (in_array($category->id, $config['categories'])) checked="checked" @endif
                    >
                    <span class="form-check-label">{{ $category->name }}</span>
                </label>
                @if ($category->activeChildren->isNotEmpty())
                    <ul style="padding-left: 20px">
                        @foreach ($category->activeChildren as $child)
                            <li>
                                <label class="form-check">
                                    <input
                                        name="categories[]"
                                        type="checkbox"
                                        class="form-check-input"
                                        value="{{ $child->id }}"
                                        @if (in_array($child->id, $config['categories'])) checked="checked" @endif
                                    >
                                    <span class="form-check-label">{{ $child->name }}</span>
                                </label>
                                @if ($child->activeChildren->isNotEmpty())
                                    <ul style="padding-left: 20px">
                                        @foreach ($child->activeChildren as $item)
                                            <li>
                                                <label class="form-check">
                                                    <input
                                                        name="categories[]"
                                                        type="checkbox"
                                                        class="form-check-input"
                                                        value="{{ $item->id }}"
                                                        @if (in_array($item->id, $config['categories'])) checked="checked" @endif
                                                    >
                                                    <span class="form-check-label">{{ $item->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</div>

