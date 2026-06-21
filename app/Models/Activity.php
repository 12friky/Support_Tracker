<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'assigned_to_id',
        'created_by_id',
        'last_updated_by_id',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function lastUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function latestLog()
    {
        return $this->hasOne(ActivityLog::class)->latestOfMany();
    }
}