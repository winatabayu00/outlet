<?php

namespace App\Models\Outlets;

use App\Enums\Table;
use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string id
 * @property string name
 * @property string address
 * @property float longitude
 * @property float latitude
 * @property \DateTimeInterface|Carbon created_at
 * */
class Outlet extends Model
{
    use HasFactory;

    protected $table = Table::OUTLETS->value;

    protected $fillable = [
        'name',
        'address',
        'longitude',
        'latitude',
    ];

    public function media(): MorphMany
    {
        return $this->morphMany(config('media-library.media_model'), name: 'model', id: 'model_uuid');
    }
}
