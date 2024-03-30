<?php

namespace App\Models\Products;

use App\Enums\Table;
use App\Models\Brands\Brand;
use App\Models\Model;
use App\Models\Outlets\Outlet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string id
 * @property string brand_id
 * @property string outlet_id
 * @property string name
 * @property float price
 * @property \DateTimeInterface|Carbon created_at
 * @property BelongsTo brand
 * @property BelongsTo outlet
 *
 * */
class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use HasUuids;

    protected $table = Table::PRODUCTS->value;

    protected $fillable = [
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'float'
    ];

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * @return BelongsTo
     */
    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    /**
     * @return MorphMany
     */
    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
