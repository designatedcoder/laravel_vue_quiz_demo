<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stat extends Model
{
    /** @use HasFactory<\Database\Factories\StatFactory> */
    use HasFactory;

    protected  $fillable = ['score', 'start_time', 'end_time'];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'created_at' => 'datetime:d-M-Y'
        ];
    }


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected  $appends = ['calculated_duration'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected function calculatedDuration(): Attribute {
        return new Attribute(
            get: fn ()=> CarbonInterval::diff($this->end_time, $this->start_time)
        );
    }

    /**
     * @return BelongsTo<User,Stat>
     */
    public function user(): BelongsTo {
       return $this->belongsTo(User::class);
    }
}
