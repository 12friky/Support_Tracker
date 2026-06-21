<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'email_notifications',
        'sms_alerts',
        'auto_save_drafts',
        'timezone',
        'language',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'sms_alerts' => 'boolean',
        'auto_save_drafts' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}