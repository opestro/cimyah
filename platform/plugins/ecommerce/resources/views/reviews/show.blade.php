@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row" id="review-section-wrapper">
        <div class="col-md-8 mb-3 mb-md-0">
            <x-core::card>
                <x-core::card.header class="flex-wrap gap-2 justify-content-between">
                    <div>
                        <h4 class="card-title d-flex justify-content-between align-items-center w-100">
                            @include('plugins/ecommerce::reviews.partials.rating', [
                                'star' => $review->star,
                            ])
                        </h4>

                        <div class="d-flex flex-wrap align-items-center gap-1 mt-2">
                            <div>
                                {{ $review->user->name ?: $review->customer_name }}
                                (<a href="mailto:{{ $review->user->email ?: $review->customer_email }}">{{ $review->user->email ?: $review->customer_email }}</a>)
                            </div>
                            <span class="d-flex align-items-center gap-1 text-muted">
                                <x-core::icon name="ti ti-clock" size="sm" />
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    {!! BaseHelper::clean($review->status->toHtml()) !!}
                </x-core::card.header>
                <x-core::card.body>
                    <p class="card-text">
                        {{ $review->comment }}
                    </p>

                    @if ($review->images)
                        <div class="row g-2 g-md-3 mb-3">
                            @foreach ($review->images as $image)
                                <div class="col-2">
                                    <a data-bb-lightbox href="{{ RvMedia::getImageUrl($image) }}">
                                        <div class="img-responsive img-responsive-1x1 rounded-3 border" style="background-image: url({{ RvMedia::getImageUrl($image) }});"></div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <div class="btn-list">
                            <x-core::button
                                color="danger"
                                :outlined="true"
                                data-bb-toggle="review-delete"
                                data-target="{{ route('reviews.destroy', $review) }}"
                            >
                                {{ trans('plugins/ecommerce::review.delete') }}
                            </x-core::button>
                            @if ($review->status == Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
                                <x-core::button
                                    color="warning"
                                    data-id="{{ $review->getKey() }}"
                                    data-bb-toggle="review-unpublish"
                                >{{ trans('plugins/ecommerce::review.unpublish') }}</x-core::button>
                            @else
                                <x-core::button
                                    color="primary"
                                    data-id="{{ $review->getKey() }}"
                                    data-bb-toggle="review-publish"
                                >{{ trans('plugins/ecommerce::review.publish') }}</x-core::button>
                            @endif
                        </div>
                    </div>
                </x-core::card.body>

                @if (auth()->user()->hasPermission('reviews.reply') || $review->reply)
                    <x-core::card.body>
                        @if (auth()->user()->hasPermission('reviews.reply'))
                            <x-core::form :url="$review->reply ? route('reviews.reply.update', [$review, $review->reply]) : route('reviews.reply', $review)" :method="$review->reply ? 'PUT' : 'POST'">
                                <h4 class="mb-2">{{ trans('plugins/ecommerce::review.reply_to_review') }}</h4>

                                @if ($review->reply)
                                    <div class="bg-body-tertiary p-3 rounded">
                                        <div class="d-flex gap-3">
                                            <div class="col-auto">
                                                <span class="avatar avatar-lg rounded" style="background-image: url({{ $review->reply->user->avatar_url }})"></span>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex flex-wrap gap-1 justify-content-between mb-2">
                                                    <div class="fw-medium">
                                                        {{ $review->reply->user->name }}
                                                        (<a href="mailto:{{ $review->reply->user->email }}">{{ $review->reply->user->email }}</a>)
                                                    </div>
                                                    <div class="text-muted">
                                                        {{ $review->reply->created_at->diffForHumans() }}
                                                    </div>
                                                </div>

                                                <div class="reply-message mb-3">
                                                    {{ $review->reply->message }}
                                                </div>

                                                <div class="reply-form" style="display: none">
                                                    @include('plugins/ecommerce::reviews.partials.reply-form', ['reply' => $review->reply])
                                                </div>

                                                <div class="text-end">
                                                    <button type="button" class="btn-link" data-bb-toggle="edit-reply">
                                                        {{ trans('core/base::forms.edit') }}
                                                    </button>

                                                    <button type="button" class="btn-link text-danger" data-bs-toggle="modal" data-bs-target="#delete-review-reply-modal">
                                                        {{ trans('core/table::table.delete') }}
                                                    </button>

                                                    <button type="submit" class="btn-link" data-bb-toggle="update-reply" style="display: none">
                                                        {{ trans('core/base::forms.update') }}
                                                    </button>

                                                    <button type="button" class="btn-link text-secondary" data-bb-toggle="cancel-edit-reply" style="display: none">
                                                        {{ trans('core/base::forms.cancel') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        @include('plugins/ecommerce::reviews.partials.reply-form', ['reply' => $review->reply])
                                    </div>

                                    <div class="text-end">
                                        <x-core::button
                                            type="submit"
                                            color="primary"
                                            icon="ti ti-send"
                                        >
                                            {{ trans('plugins/ecommerce::review.reply') }}
                                        </x-core::button>
                                    </div>
                                @endif
                            </x-core::form>
                        @endif
                    </x-core::card.body>
                @endif
            </x-core::card>
        </div>
        <div class="col-md-4">
            <x-core::card>
                <x-core::card.header>
                    <h4 class="card-title">
                        {{ trans('plugins/ecommerce::review.product') }}
                    </h4>
                </x-core::card.header>

                <x-core::card.body>
                    <div class="d-flex gap-3 align-items-start">
                        <img
                            class="img-thumbnail"
                            src="{{ RvMedia::getImageUrl($review->product->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                            alt="{{ $review->product->name }}"
                            style="width: 15%"
                        >
                        <div>
                            <h4>
                                <a href="{{ $review->product->url }}" target="_blank">
                                    {{ $review->product->name }}
                                </a>
                            </h4>
                            <div>
                                @include('plugins/ecommerce::reviews.partials.rating', [
                                    'star' => $review->product->reviews_avg_star,
                                ])
                                <span>({{ number_format($review->product->reviews_count) }})</span>
                            </div>
                        </div>
                    </div>
                </x-core::card.body>
            </x-core::card>
        </div>
    </div>
@endsection

@push('footer')
    <x-core::modal.action
        type="danger"
        id="delete-review-modal"
        :title="trans('plugins/ecommerce::review.delete_modal.title')"
        :description="trans('plugins/ecommerce::review.delete_modal.description')"
        :submit-button-label="trans('plugins/ecommerce::review.delete')"
        :submit-button-attrs="['id' => 'confirm-delete-review-button']"
    />

    @if ($review->reply)
        <x-core::modal.action
            type="danger"
            id="delete-review-reply-modal"
            :title="trans('plugins/ecommerce::review.delete_reply_modal.title')"
            :description="trans('plugins/ecommerce::review.delete_reply_modal.description')"
            :form-action="route('reviews.reply.destroy', [$review, $review->reply])"
            form-method="DELETE"
            :submit-button-label="trans('plugins/ecommerce::review.delete')"
            :submit-button-attrs="['data-bb-toggle' => 'review-reply-delete']"
        />
    @endif
@endpush
