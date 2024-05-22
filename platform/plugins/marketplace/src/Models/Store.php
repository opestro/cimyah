<?php

namespace Botble\Marketplace\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\Avatar;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Discount;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\QueryBuilders\StoreQueryBuilder;
use Botble\Ecommerce\Traits\LocationTrait;
use Botble\Media\Facades\RvMedia;
use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Store extends BaseModel
{
    use LocationTrait;

    protected $table = 'mp_stores';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'customer_id',
        'logo',
        'cover_image',
        'description',
        'content',
        'status',
        'company',
        'zip_code',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
        'address' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (Store $store) {
            $store->products()->each(fn (Product $product) => $product->delete());
            $store->discounts()->delete();
            $store->orders()->update(['store_id' => null]);

            $folder = Storage::path($store->upload_folder);
            if (File::isDirectory($folder) && Str::endsWith($store->upload_folder, '/' . ($store->slug ?: $store->id))) {
                File::deleteDirectory($folder);
            }
        });

        static::updating(function (Store $store) {
            if ($store->getOriginal('status') != $store->status) {
                $status = $store->status;

                $store->products()->update(['status' => $status]);
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->where('is_finished', 1);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'store_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return RvMedia::getImageUrl($this->logo);
        }

        try {
            return (new Avatar())->create($this->name)->toBase64();
        } catch (Exception) {
            return RvMedia::getDefaultImage();
        }
    }

    public function reviews(): HasMany
    {
        return $this
            ->hasMany(Product::class)
            ->join('ec_reviews', 'ec_products.id', '=', 'ec_reviews.product_id');
    }

    protected function uploadFolder(): Attribute
    {
        return Attribute::make(
            get: function () {
                $folder = $this->id ? 'stores/' . ($this->slug ?: $this->id) : 'stores';

                return apply_filters('marketplace_store_upload_folder', $folder, $this);
            }
        );
    }

    public static function handleCommissionEachCategory(array $data): array
    {
        $commissions = [];
        CategoryCommission::query()->truncate();
        foreach ($data as $datum) {
            if (! $datum['categories']) {
                continue;
            }

            $categories = json_decode($datum['categories'], true);

            if (! is_array($categories) || ! count($categories)) {
                continue;
            }

            foreach ($categories as $category) {
                $commission = CategoryCommission::query()->firstOrNew([
                    'product_category_id' => $category['id'],
                ]);

                if (! $commission) {
                    continue;
                }

                $commission->commission_percentage = $datum['commission_fee'];
                $commission->save();
                $commissions[] = $commission;
            }
        }

        return $commissions;
    }

    public static function getCommissionEachCategory(): array
    {
        $commissions = CategoryCommission::query()->with(['category'])->get();
        $data = [];
        foreach ($commissions as $commission) {
            if (! $commission->category) {
                continue;
            }

            $data[$commission->commission_percentage]['commission_fee'] = $commission->commission_percentage;
            $data[$commission->commission_percentage]['categories'][] = [
                'id' => $commission->product_category_id,
                'value' => $commission->category->name,
            ];
        }

        return $data;
    }

    public function newEloquentBuilder($query): StoreQueryBuilder
    {
        return new StoreQueryBuilder($query);
    }

    public function getMetaData(string $key, bool $single = false): array|string|null
    {
        if (in_array($key, ['cover_image', 'background'])) {
            return $this->cover_image;
        }

        return parent::getMetaData($key, $single);
    }
}
