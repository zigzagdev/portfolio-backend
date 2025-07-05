<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Common\Domain\Enum\PostVisibility;

class Post extends Model
{
    protected $connection = 'mysql';

    protected $fillable = [
        'content',
        'user_id',
        'media_path',
        'visibility',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'visibility' => PostVisibility::class,
    ];

    public function getVisibilityLabelAttribute(): string
    {
        return $this->visibility->toLabel();
    }
}
