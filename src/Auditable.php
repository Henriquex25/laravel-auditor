<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Henriquex25\LaravelAuditor\Model\Audit;
use Henriquex25\LaravelAuditor\Facades\Auditor;
use Henriquex25\LaravelAuditor\Enums\AuditAction;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Henriquex25\LaravelAuditor\Observers\AuditableObserver;

trait Auditable
{
    public static function bootAuditable()
    {
        if (static::isAuditingEnabled()) {
            static::observe(new AuditableObserver());

            static::morphToManyAttached(function ($relation, $parent, $attributes) {
                Auditor::run([
                    'action'         => AuditAction::ATTACHED,
                    'auditable_id'   => $parent->id,
                    'auditable_type' => get_class($parent),
                    'details'        => [
                        'relation'   => $relation,
                        'attributes' => $attributes
                    ],
                ]);
            });

            static::morphToManyDetached(function ($relation, $parent, $attributes) {
                Auditor::run([
                    'action'         => AuditAction::DETACHED,
                    'auditable_id'   => $parent->id,
                    'auditable_type' => get_class($parent),
                    'details'        => [
                        'relation'   => $relation,
                        'attributes' => $attributes
                    ],
                ]);
            });
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
        return $this->morphMany(Audit::class, 'auditable');
    }
}