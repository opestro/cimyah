<?php

namespace Botble\Newsletter\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Newsletter\Enums\NewsletterStatusEnum;
use Botble\Newsletter\Events\SubscribeNewsletterEvent;
use Botble\Newsletter\Events\UnsubscribeNewsletterEvent;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Newsletter\Http\Requests\NewsletterRequest;
use Botble\Newsletter\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PublicController extends BaseController
{
    public function postSubscribe(NewsletterRequest $request)
    {
        do_action('form_extra_fields_validate', $request, NewsletterForm::class);

        $newsletterForm = NewsletterForm::create();
        $newsletterForm->setRequest($request);

        $newsletterForm->onlyValidatedData()->saving(function (NewsletterForm $form) {
            /**
             * @var NewsletterRequest $request
             */
            $request = $form->getRequest();

            /**
             * @var Newsletter $newsletter
             */
            $newsletter = $form->getModel()->newQuery()->firstOrNew([
                'email' => $request->input('email'),
            ], [
                ...$form->getRequestData(),
                'status' => NewsletterStatusEnum::SUBSCRIBED,
            ]);

            $newsletter->save();

            SubscribeNewsletterEvent::dispatch($newsletter);
        });

        return $this
            ->httpResponse()
            ->setMessage(__('Subscribe to newsletter successfully!'));
    }

    public function getUnsubscribe(int|string $id, Request $request)
    {
        if (! URL::hasValidSignature($request)) {
            abort(404);
        }

        /**
         * @var Newsletter $newsletter
         */
        $newsletter = Newsletter::query()
            ->where([
                'id' => $id,
                'status' => NewsletterStatusEnum::SUBSCRIBED,
            ])
            ->first();

        if ($newsletter) {
            $newsletter->update(['status' => NewsletterStatusEnum::UNSUBSCRIBED]);

            UnsubscribeNewsletterEvent::dispatch($newsletter);

            return $this
                ->httpResponse()
                ->setNextUrl(BaseHelper::getHomepageUrl())
                ->setMessage(__('Unsubscribe to newsletter successfully'));
        }

        return $this
            ->httpResponse()
            ->setError()
            ->setNextUrl(BaseHelper::getHomepageUrl())
            ->setMessage(__('Your email does not exist in the system or you have unsubscribed already!'));
    }
}
