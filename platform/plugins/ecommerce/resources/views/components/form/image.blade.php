<div class="list-photo-hover-overlay">
    <ul class="photo-overlay-actions">
        <li>
            <a
                class="mr10 btn-trigger-edit-product-image"
                data-bs-toggle="tooltip"
                data-placement="bottom"
                data-bs-original-title="{{ trans('core/base::base.change_image') }}"
            >
                <x-core::icon name="ti ti-edit" />
            </a>
        </li>
        <li>
            <a
                class="mr10 btn-trigger-remove-product-image"
                data-bs-toggle="tooltip"
                data-placement="bottom"
                data-bs-original-title="{{ trans('core/base::base.delete_image') }}"
            >
                <x-core::icon name="ti ti-trash" />
            </a>
        </li>
    </ul>
</div>
<div class="custom-image-box image-box">
    <input
        class="image-data"
        name="{{ $name }}"
        type="hidden"
        value="{{ $value }}"
    >
    <img
        class="preview_image"
        src="{{ $thumb }}"
        alt="{{ trans('core/base::base.preview_image') }}"
    >
    <div class="image-box-actions">
        <a
            class="btn-images"
            data-result="{{ $name }}"
            data-action="select-image"
        >
            {{ trans('core/base::forms.choose_image') }}
        </a> |
        <a class="btn_remove_image">
            <span></span>
        </a>
    </div>
</div>
