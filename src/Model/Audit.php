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
        'causer_id',
        'causer_type',
        'details',
    ];

    protected $casts = [
        'when'    => 'datetime',
        'details' => 'json',
        'actions' => AuditActionEnum::class
    ];

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    public function getNewValues(): ?array
    {
        return $this->details['new_values'] ?? null;
    }

    public function getOldValues(): array
    {
        return $this->details['old_values'] ?? null;
    }
}
