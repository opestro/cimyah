<?php

namespace Botble\Ecommerce\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Http\Requests\ReviewReplyRequest;
use Botble\Ecommerce\Models\Review;
use Botble\Ecommerce\Models\ReviewReply;

class ReviewReplyController extends BaseController
{
    public function store(Review $review, ReviewReplyRequest $request)
    {
        $userId = $request->user()->id;

        if (
            ReviewReply::query()
                ->where('review_id', $review->getKey())
                ->where('user_id', $userId)
                ->exists()
        ) {
            return $this
                ->httpResponse()
                ->setMessage(trans('plugins/ecommerce::review.already_replied'))
                ->setError();
        }

        $review->reply()->create([
            'user_id' => $userId,
            'message' => $request->input('message'),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/ecommerce::review.replied_success_message'));
    }

    public function update(Review $review, ReviewReply $reply, ReviewReplyRequest $request)
    {
        if ($reply->review()->isNot($review)) {
            abort(404);
        }

        $reply->update([
            'message' => $request->input('message'),
        ]);

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Review $review, ReviewReply $reply)
    {
        return DeleteResourceAction::make($reply);
    }
}
