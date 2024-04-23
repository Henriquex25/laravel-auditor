<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor;

use Chelout\RelationshipEvents\Concerns\HasMorphToManyEvents;
use Henriquex25\LaravelAuditor\Model\Audit;
use Henriquex25\LaravelAuditor\Observers\AuditableObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

trait Auditable
{
    use HasMorphToManyEvents;

    public static function bootAuditable()
    {
        if (static::isAuditingEnabled()) {
            static::observe(new AuditableObserver());
        }
    }

    public static function isAuditingEnabled(): bool
    {
        if (App::runningInConsole()) {
            return Config::get('audit.enabled', true) && Config::get('audit.audit_in_console', false);
        }

        return Config::get('audit.enabled', true);
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'causer');
    }
}
