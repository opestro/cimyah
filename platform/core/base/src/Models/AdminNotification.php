<?php

namespace Botble\Base\Models;

use Botble\Base\Casts\SafeContent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class AdminNotification extends BaseModel
{
    use MassPrunable;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'title',
        'action_label',
        'action_url',
        'description',
        'permission',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'title' => SafeContent::class,
        'action_label' => SafeContent::class,
        'action_url' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function markAsRead(): void
    {
        $this->update([
            'read_at' => Carbon::now(),
        ]);
    }

    public function prunable(): Builder|BaseQueryBuilder
    {
        return static::query()->where('created_at', '<=', Carbon::now()->subMonth());
    }

    public static function countUnread(): int
    {
        /**
         * @var AdminNotificationQueryBuilder $adminQuery
         */
        $adminQuery = AdminNotification::query();

        /**
         * @var Builder $query
         */
        $query = $adminQuery->hasPermission();

        return $query
            ->whereNull('read_at')
            ->select('action_url')
            ->count();
    }

    public function isAbleToAccess(): bool
    {
        $user = Auth::guard()->user();

        return ! $this->permission || $user->isSuperUser() || $user->hasPermission($this->permission);
    }

    protected static function booted(): void
    {
        static::creating(function (AdminNotification $notification) {
            if ($notification->action_url) {
                $notification->action_url = str_replace(url(''), '', $notification->action_url);
            }
        });
    }

    public function newEloquentBuilder($query): AdminNotificationQueryBuilder
    {
        return new AdminNotificationQueryBuilder($query);
    }
}
