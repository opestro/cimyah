<div @class(['col-12', 'col-md-8' => $showAvgRating])>
    <h4>{{ __('Add your review') }}</h4>

    @if (isset($checkReview) && ! $checkReview['error'])
        <p>
            {{ __('Your email address will not be published. Required fields are marked *') }}
            <span class="required"></span>
        </p>
    @endif

    @guest('customer')
        <p class="text-danger">
            {!! BaseHelper::clean(
                __('Please <a href=":link">login</a> to write review!', ['link' => route('customer.login')]),
            ) !!}
        </p>
    @endguest

    @if (isset($checkReview) && $checkReview['error'])
        <p class="text-warning">{{ $checkReview['message'] }}</p>
    @else
        <x-core::form :url="route('public.reviews.create')" method="post" :files="true">
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="d-flex align-items-center mb-3">
                <label class="form-label mb-0 required" for="rating">{{ __('Your rating:') }}</label>
                <div class="form-rating-stars ms-2">
                    @for ($i = 5; $i >= 1; $i--)
                        <input
                            class="btn-check"
                            id="rating-star-{{ $i }}"
                            name="star"
                            type="radio"
                            value="{{ $i }}"
                            @checked($i === 5)
                        >
                        <label for="rating-star-{{ $i }}" title="{{ $i }} stars">
                            <x-core::icon name="ti ti-star-filled" />
                        </label>
                    @endfor
                </div>
            </div>

            <div class="mb-3">
                <label for="comment" class="form-label required">
                    {{ __('Review') }}:
                </label>
                <textarea
                    class="form-control"
                    name="comment"
                    required
                    rows="8"
                    placeholder="{{ __('Write your review') }}"
                    @disabled(! auth('customer')->check())
                ></textarea>
            </div>

            <script type="text/x-custom-template" id="review-image-template">
                <span class="image-viewer__item" data-id="__id__">
                    <img src="{{ RvMedia::getDefaultImage() }}" alt="Preview" class="img-responsive d-block">
                    <span class="image-viewer__icon-remove">
                        <x-core::icon name="ti ti-x" />
                    </span>
                </span>
            </script>

            <div class="image-upload__viewer d-flex">
                <div class="image-viewer__list position-relative">
                    <div class="image-upload__uploader-container">
                        <div class="d-table">
                            <div class="image-upload__uploader">
                                <x-core::icon name="ti ti-photo" />
                                <div class="image-upload__text">{{ __('Upload photos') }}</div>
                                <input
                                    class="image-upload__file-input"
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

            <div role="alert" class="image-upload-info alert alert-info p-2">
                <div class="small d-flex align-items-center gap-1">
                    <x-core::icon name="ti ti-info-circle" />

                    {{ __('You can upload up to :total photos, each photo maximum size is :max kilobytes.', [
                        'total' => EcommerceHelper::reviewMaxFileNumber(),
                        'max' => EcommerceHelper::reviewMaxFileSize(true),
                    ]) }}
                </div>
            </div>

            <button
                type="submit"
                @class([
                    $reviewButtonClass ?? 'btn btn-primary',
                    'disabled' => ! auth('customer')->check(),
                ])
                @disabled(! auth('customer')->check())
            >
                {{ __('Submit') }}
            </button>
        </x-core::form>
    @endif
</div>
