<x-core::form :url="route('public.reviews.create')" method="post" class="ecommerce-form-review-product" :files="true">
    <input name="product_id" type="hidden" value="{{ $product ? $product->id : '' }}">

    <div class="d-flex align-items-start gap-2 mb-3">
        <div>
            <h2 class="modal-title fs-5 ecommerce-product-name mb-2" id="product-review-modal-label">{!! BaseHelper::clean($product ? $product->name : '') !!}</h2>

            <div class="ecommerce-form-rating-stars">
                @for ($i = 5; $i >= 1; $i--)
                    <input
                        class="btn-check"
                        id="rating-star-{{ $i }}"
                        name="star"
                        type="radio"
                        value="{{ $i }}"
                        required
                    >
                    <label for="rating-star-{{ $i }}" title="{{ $i }} stars">
                        <x-core::icon name="ti ti-star-filled" class="ecommerce-icon" />
                    </label>
                @endfor
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <label class="required"for="txt-comment">{{ __('Review') }}:</label>
            <textarea
                class="form-control"
                id="txt-comment"
                name="comment"
                aria-required="true"
                required
                rows="5"
                placeholder="{{ __('Write your review') }}"
            ></textarea>
        </div>
        <div class="col-12 mb-3 form-group">
            <x-core::custom-template id="ecommerce-review-image-template">
                <span class="ecommerce-image-viewer__item" data-id="__id__">
                    <img src="{{ RvMedia::getDefaultImage() }}" alt="Preview" class="img-responsive d-block">
                    <span class="ecommerce-image-viewer__icon-remove image-viewer__icon-remove">
                        <span class="ecommerce-icon">
                            <svg>
                                <use href="#ecommerce-icon-cross" xlink:href="#ecommerce-icon-cross"></use>
                            </svg>
                        </span>
                    </span>
                </span>
            </x-core::custom-template>
            <div class="ecommerce-image-upload__viewer d-flex">
                <div class="ecommerce-image-viewer__list position-relative">
                    <div class="ecommerce-image-upload__uploader-container">
                        <div class="d-table">
                            <div class="ecommerce-image-upload__uploader">
                                <span class="ecommerce-icon ecommerce-image-upload__icon">
                                    <svg>
                                        <use
                                            href="#ecommerce-icon-image"
                                            xlink:href="#ecommerce-icon-image"
                                        ></use>
                                    </svg>
                                </span>
                                <div class="ecommerce-image-upload__text">{{ __('Upload photos') }}</div>
                                <input
                                    class="ecommerce-image-upload__file-input"
                                    name="images[]"
                                    data-max-files="{{ EcommerceHelper::reviewMaxFileNumber() }}"
                                    data-max-size="{{ EcommerceHelper::reviewMaxFileSize(true) }}"
                                    data-max-size-message="{{ trans('validation.max.file', ['attribute' => '__attribute__', 'max' => '__max__']) }}"
                                    type="file"
                                    accept="image/png,image/jpeg,image/jpg"
                                    multiple="multiple"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="help-block">
                {{ __('You can upload up to :total photos, each photo maximum size is :max kilobytes', [
                    'total' => EcommerceHelper::reviewMaxFileNumber(),
                    'max' => EcommerceHelper::reviewMaxFileSize(true),
                ]) }}
            </div>
        </div>
        <div class="col-12">
            <div class="alert alert-warning alert-message d-none"></div>
        </div>
        <div class="col-12">
            <button
                class="btn btn-primary px-3"
                type="submit"
            >
                <span class="ecommerce-icon d-inline-block me-1">
                    <svg>
                        <use
                            href="#ecommerce-icon-send"
                            xlink:href="#ecommerce-icon-send"
                        ></use>
                    </svg>
                </span>
                <span>{{ __('Submit Review') }}</span>
            </button>
        </div>
    </div>

    <p class="mt-3 mb-0">{{ __('Your email address will not be published. Required fields are marked *') }}</p>
</x-core::form>
