<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action',
        'user_id',
        'when',
        'ip',
        'auditable_id',
        'auditable_type',
        'details',
    ];

    protected $casts = [
        'when'    => 'datetime',
        'details' => 'json',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}