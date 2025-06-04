<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'visibility',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function visibility(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value === 0 ? 'public' : 'private',
            set: fn ($value) => $value === 'public' ? 0 : 1,
        );
    }
}
