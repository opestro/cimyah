<?php

namespace Botble\SocialLogin\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\SocialLogin\Http\Requests\FacebookDataDeletionRequestCallbackRequest;
use Botble\SocialLogin\Supports\FacebookDataDeletionSignedRequestParser;
use Illuminate\Support\Str;

class FacebookDataDeletionRequestCallbackController extends BaseController
{
    public function store(
        FacebookDataDeletionRequestCallbackRequest $request,
        FacebookDataDeletionSignedRequestParser $signedRequestParser
    ) {
        $data = $signedRequestParser->parse($request->input('signed_request'));

        if (! $data) {
            return response()->json([
                'error' => 'Invalid signed request',
            ]);
        }

        return response()->json([
            'url' => route('facebook-deletion-status', ['id' => $confirmationCode = Str::uuid()]),
            'confirmation_code' => $confirmationCode,
        ]);
    }

    public function show(string $id)
    {
        if (! Str::isUuid($id)) {
            abort(404);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Your data deletion request is pending. We will notify you once it is completed.',
        ]);
    }
}
