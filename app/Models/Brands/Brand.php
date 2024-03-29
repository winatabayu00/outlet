<?php

namespace App\Models\Brands;

use App\Enums\Table;
use App\Models\Model;
use App\Models\Outlets\Outlet;
use App\Models\Products\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string id
 * @property string name
 * @property \DateTimeInterface|Carbon created_at
 * @property HasMany outlets
 * @property HasMany products
 * */
class Brand extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasUuids;

    protected $table = Table::BRANDS->value;

    public array $relationChecking = [
        'outlets', 'products'
    ];

    public array $searchable = [
        'name'
    ];

    protected $fillable = [
        'name'
    ];

    /**
     * @return HasMany
     */
    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class, 'brand_id');
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
