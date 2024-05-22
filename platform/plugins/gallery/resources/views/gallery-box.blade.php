{!! Form::hidden('gallery', $value ? json_encode($value) : null, [
    'id' => 'gallery-data',
    'class' => 'form-control',
]) !!}
<div>
    <div class="list-photos-gallery">
        <div
            class="row"
            id="list-photos-items"
        >
            @if (!empty($value))
                @foreach ($value as $key => $item)
                    <div
                        class="col-md-2 col-sm-3 col-4 photo-gallery-item"
                        data-id="{{ $key }}"
                        data-img="{{ $imageUrl = Arr::get($item, 'img') }}"
                        data-description="{{ $description = Arr::get($item, 'description') }}"
                    >
                        <div class="gallery_image_wrapper">
                            {{ RvMedia::image($imageUrl, $description, 'thumb') }}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="d-flex gap-2">
        <a
            href="#"
            class="btn_select_gallery"
        >{{ trans('plugins/gallery::gallery.select_image') }}</a>
        <a
            href="#"
            class="text-danger reset-gallery @if (empty($value)) hidden @endif"
        >{{ trans('plugins/gallery::gallery.reset') }}</a>
    </div>
</div>

<x-core::modal
    id="edit-gallery-item"
    :title="trans('plugins/gallery::gallery.update_photo_description')"
>
    <input
        type="text"
        class="form-control"
        id="gallery-item-description"
        placeholder="{{ trans('plugins/gallery::gallery.update_photo_description_placeholder') }}"
    >

    <x-slot:footer>
        <div class="btn-list">
            <x-core::button
                type="button"
                color="danger"
                id="delete-gallery-item"
            >
                {{ trans('plugins/gallery::gallery.delete_photo') }}
            </x-core::button>
            <x-core::button
                type="button"
                data-bs-dismiss="modal"
            >
                {{ trans('core/base::forms.cancel') }}
            </x-core::button>
            <x-core::button
                type="button"
                color="primary"
                id="update-gallery-item"
            >
                {{ trans('core/base::forms.update') }}
            </x-core::button>
        </div>
    </x-slot:footer>
</x-core::modal>
