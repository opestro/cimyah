<?php

namespace Botble\ACL\Concerns;

use Botble\ACL\Models\UserMeta;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

trait HasPreferences
{
    protected Collection $metaValues;

    protected bool $loadedMetaValues = false;

    public function meta(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    public function setMeta(string $key, mixed $value): bool
    {
        $meta = $this->meta()->firstOrCreate([
            'key' => $key,
        ]);

        return $meta->update(['value' => $value]);
    }

    public function getMeta(string $key, mixed $default = null): mixed
    {
        $this->loadMeta();

        $meta = $this->metaValues
            ->where('key', $key)
            ->first();

        if (! empty($meta)) {
            return $meta->value;
        }

        return $default;
    }

    public function loadMeta(bool $force = false): void
    {
        if (! $this->loadedMetaValues || $force) {
            $this->metaValues = $this->meta()->get();
            $this->loadedMetaValues = true;
        }
    }
}
