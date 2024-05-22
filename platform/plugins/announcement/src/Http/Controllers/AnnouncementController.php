<?php

namespace ArchiElite\Announcement\Http\Controllers;

use ArchiElite\Announcement\Forms\AnnouncementForm;
use ArchiElite\Announcement\Http\Requests\AnnouncementRequest;
use ArchiElite\Announcement\Models\Announcement;
use ArchiElite\Announcement\Tables\AnnouncementTable;
use Botble\Base\Events\BeforeUpdateContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends BaseController
{
    public function __construct(protected BaseHttpResponse $response)
    {
    }

    public function index(AnnouncementTable $table): View|JsonResponse
    {
        PageTitle::setTitle(trans('plugins/announcement::announcements.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder): string
    {
        PageTitle::setTitle(trans('plugins/announcement::announcements.create'));

        return $formBuilder->create(AnnouncementForm::class)->renderForm();
    }

    public function store(AnnouncementRequest $request): BaseHttpResponse
    {
        $announcement = Announcement::query()->create($request->validated());

        event(new CreatedContentEvent('announcement', $request, $announcement));

        return $this->response
            ->setPreviousUrl(route('announcements.index'))
            ->setNextUrl(route('announcements.edit', $announcement->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Announcement $announcement, FormBuilder $formBuilder): string
    {
        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $announcement->name]));

        return $formBuilder->create(AnnouncementForm::class, ['model' => $announcement])->renderForm();
    }

    public function update(Announcement $announcement, AnnouncementRequest $request): BaseHttpResponse
    {
        event(new BeforeUpdateContentEvent($request, $announcement));

        $announcement->update($request->validated());

        event(new UpdatedContentEvent('announcement', $request, $announcement));

        return $this->response
            ->setPreviousUrl(route('announcements.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Announcement $announcement, Request $request): BaseHttpResponse
    {
        try {
            $announcement->delete();

            event(new DeletedContentEvent('announcement', $request, $announcement));

            return $this->response
                ->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $this->response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }
}
