<?php

namespace Botble\Ecommerce\Commands;

use Botble\Ecommerce\Enums\DeletionRequestStatusEnum;
use Botble\Ecommerce\Models\CustomerDeletionRequest;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cms:ecommerce:cancel-expired-deletion-requests', description: 'Cancel expired deletion requests')]
class CancelExpiredDeletionRequests extends Command
{
    public function handle(): int
    {
        $expirationDays = 3;

        $deletionRequests = CustomerDeletionRequest::query()
            ->where('status', DeletionRequestStatusEnum::WAITING_FOR_CONFIRMATION)
            ->where('created_at', '<=', now()->subDays($expirationDays))
            ->get();

        foreach ($deletionRequests as $deletionRequest) {
            $deletionRequest->update(['status' => DeletionRequestStatusEnum::CANCELED]);
        }

        $this->components->info('Expired deletion requests have been canceled successfully.');

        return self::SUCCESS;
    }
}
