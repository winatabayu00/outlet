<?php

namespace App\Models\Products;

use App\Enums\Table;
use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string id
 * @property string name
 * @property float price
 * @property \DateTimeInterface|Carbon created_at
 *
 * */
class Product extends Model
{
    use HasFactory;

    protected $table = Table::PRODUCTS->value;

    protected $fillable = [
        'name',
        'price',
    ];

    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
