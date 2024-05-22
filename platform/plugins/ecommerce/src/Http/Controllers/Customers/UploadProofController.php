<?php

namespace Botble\Ecommerce\Http\Controllers\Customers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Ecommerce\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadProofController extends BaseController
{
    public function upload(int|string $id, Request $request)
    {
        $order = Order::query()
            ->where('user_id', auth('customer')->id())
            ->findOrFail($id);

        $request->validate([
            'file' => ['required', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:2048'],
        ]);

        $file = $request->file('file');

        $storage = Storage::disk('local');

        if ($order->proof_file) {
            $storage->delete($order->proof_file);
        }

        if (! $storage->exists('proofs')) {
            $storage->makeDirectory('proofs');
        }

        $order->update([
            'proof_file' => $storage->putFileAs('proofs', $file, sprintf('%s-%s', $order->getKey(), $file->getClientOriginalName())),
        ]);

        return $this
            ->httpResponse()
            ->setMessage(__('Uploaded proof successfully'));
    }

    public function download(int|string $id)
    {
        $order = Order::query()
            ->where('user_id', auth('customer')->id())
            ->findOrFail($id);

        $storage = Storage::disk('local');

        if (! $storage->exists($order->proof_file)) {
            abort(404);
        }

        return $storage->download($order->proof_file);
    }
}
