<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetRequest extends Model
{
    public $fillable = [
        'user_id',
        'token',
        'requested_at',
        'expired_at',
    ];

    protected $connection = 'mysql';
    protected $table = 'password_reset_requests';

    protected $casts = [
        'requested_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
