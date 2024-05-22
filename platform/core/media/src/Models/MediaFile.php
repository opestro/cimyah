<?php

namespace Botble\Media\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Models\BaseModel;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class MediaFile extends BaseModel
{
    use SoftDeletes;

    protected $table = 'media_files';

    protected $fillable = [
        'name',
        'mime_type',
        'type',
        'size',
        'url',
        'options',
        'folder_id',
        'user_id',
        'alt',
        'visibility',
    ];

    protected $casts = [
        'options' => 'json',
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::forceDeleting(fn (MediaFile $file) => RvMedia::deleteFile($file));
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id')->withDefault();
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $type = 'document';

                foreach (RvMedia::getConfig('mime_types', []) as $key => $value) {
                    if (in_array($attributes['mime_type'], $value)) {
                        $type = $key;

                        break;
                    }
                }

                return $type;
            }
        );
    }

    protected function humanSize(): Attribute
    {
        return Attribute::get(fn () => BaseHelper::humanFilesize($this->size));
    }

    protected function icon(): Attribute
    {
        return Attribute::get(function () {
            $icon = match ($this->type) {
                'image' => 'ti ti-photo',
                'video' => 'ti ti-video',
                'pdf' => 'ti ti-file-type-pdf',
                'excel' => 'ti ti-file-spreadsheet',
                default => 'ti ti-file',
            };

            return BaseHelper::renderIcon($icon);
        });
    }

    protected function previewUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $preview = null;

            switch ($this->type) {
                case 'image':
                case 'pdf':
                case 'text':
                case 'video':
                    $preview = RvMedia::url($this->url);

                    break;
                case 'document':
                    if ($this->mime_type === 'application/pdf') {
                        $preview = RvMedia::url($this->url);

                        break;
                    }

                    $config = config('core.media.media.preview.document', []);
                    if (
                        Arr::get($config, 'enabled') &&
                        Request::ip() !== '127.0.0.1' &&
                        in_array($this->mime_type, Arr::get($config, 'mime_types', [])) &&
                        $url = Arr::get($config, 'providers.' . Arr::get($config, 'default'))
                    ) {
                        $preview = Str::replace('{url}', urlencode(RvMedia::url($this->url)), $url);
                    }

                    break;
            }

            return $preview;
        });
    }

    protected function previewType(): Attribute
    {
        return Attribute::get(fn () => Arr::get(config('core.media.media.preview', []), "$this->type.type"));
    }

    public function canGenerateThumbnails(): bool
    {
        return RvMedia::canGenerateThumbnails($this->mime_type);
    }

    public static function createName(string $name, int|string|null $folder): string
    {
        $index = 1;
        $baseName = $name;
        while (self::query()->where('name', $name)->where('folder_id', $folder)->withTrashed()->exists()) {
            $name = $baseName . '-' . $index++;
        }

        return $name;
    }

    public static function createSlug(string $name, string $extension, ?string $folderPath): string
    {
        if (setting('media_use_original_name_for_file_path')) {
            $slug = $name;
        } else {
            $slug = Str::slug($name, '-', ! RvMedia::turnOffAutomaticUrlTranslationIntoLatin() ? 'en' : false);
        }

        $index = 1;
        $baseSlug = $slug;
        while (File::exists(RvMedia::getRealPath(rtrim($folderPath, '/') . '/' . $slug . '.' . $extension))) {
            $slug = $baseSlug . '-' . $index++;
        }

        if (empty($slug)) {
            $slug = $slug . '-' . time();
        }

        return Str::limit($slug, end: '') . '.' . $extension;
    }
}
