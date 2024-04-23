<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor\Model;

use Henriquex25\LaravelAuditor\Enums\AuditActionEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action',
        'when',
        'ip_address',
        'auditable_id',
        'auditable_type',
        'causer_id',
        'causer_type',
        'details',
    ];

    protected $casts = [
        'when'    => 'datetime',
        'details' => 'json',
        'actions' => AuditActionEnum::class
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }
}
