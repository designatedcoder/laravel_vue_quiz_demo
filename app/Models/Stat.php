<?php

namespace App\Models;

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
     * @return BelongsTo<User,Stat>
     */
    public function user(): BelongsTo {
       return $this->belongsTo(User::class);
    }
}
