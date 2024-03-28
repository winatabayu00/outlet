<?php

namespace App\Models\Outlets;

use App\Enums\Table;
use App\Models\Brands\Brand;
use App\Models\Model;
use App\Models\Products\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string id
 * @property string name
 * @property string address
 * @property float longitude
 * @property float latitude
 * @property \DateTimeInterface|Carbon created_at
 * @property BelongsTo brand
 * @property HasMany products
 * */
class Outlet extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasUuids;

    const MORPH_ALIAS = 'outlet';

    protected $table = Table::OUTLETS->value;

    protected $fillable = [
        'name',
        'address',
        'longitude',
        'latitude',
    ];

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'outlet_id');
    }

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
