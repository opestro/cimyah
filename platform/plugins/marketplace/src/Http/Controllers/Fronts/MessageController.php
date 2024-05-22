<?php

namespace Botble\Marketplace\Http\Controllers\Fronts;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Marketplace\Facades\MarketplaceHelper;
use Botble\Marketplace\Models\Message;
use Botble\Marketplace\Tables\MessageTable;

class MessageController extends BaseController
{
    public function index(MessageTable $messageTable)
    {
        $this->pageTitle(__('Messages'));

        return $messageTable->renderTable();
    }

    public function show(string $id)
    {
        $message = Message::query()
            ->where('store_id', auth('customer')->user()->store->id)
            ->with(['store', 'customer'])
            ->findOrFail($id);

        $this->pageTitle(__('Viewing message #:id', ['id' => $message->getKey()]));

        return MarketplaceHelper::view('vendor-dashboard.messages.show', compact('message'));
    }

    public function destroy(string $id)
    {
        $message = Message::query()
            ->where('store_id', auth('customer')->user()->store->id)
            ->findOrFail($id);

        return DeleteResourceAction::make($message);
    }
}
