<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Review;

class PublishedReviewController extends BaseController
{
    public function store(string|int $id)
    {
        $review = Review::query()
            ->whereIn('status', [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING])
            ->findOrFail($id);

        $review->update(['status' => BaseStatusEnum::PUBLISHED]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::review.published_success'));
    }

    public function destroy(string|int $id)
    {
        $review = Review::query()
            ->wherePublished()
            ->findOrFail($id);

        $review->update(['status' => BaseStatusEnum::DRAFT]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::review.unpublished_success'));
    }
}
