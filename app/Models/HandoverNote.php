<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverNote extends Model
{
    protected $fillable = [
        'note_date',
        'note',
        'created_by_id',
    ];

    protected $casts = [
        'note_date' => 'date',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
