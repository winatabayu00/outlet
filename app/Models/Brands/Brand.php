<?php

namespace App\Models\Brands;

use App\Enums\Table;
use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string id
 * @property string name
 * @property \DateTimeInterface|Carbon created_at
 * */
class Brand extends Model
{
    use HasFactory;

    protected $table = Table::BRANDS->value;

    protected $fillable = [
        'name'
    ];

    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
