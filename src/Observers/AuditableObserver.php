<?php

namespace Henriquex25\LaravelAuditor\Observers;

use Henriquex25\LaravelAuditor\Enums\AuditActionEnum;
use Henriquex25\LaravelAuditor\Facades\Auditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AuditableObserver
{
    public $afterCommit;

    public function __construct()
    {
        $this->afterCommit = Config::get('audit.after_commit', true);
    }

    public function created(Model $model): void
    {
        $data = $model->toArray();

        Auditor::run([
            'action'      => AuditActionEnum::CREATED,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => $data
        ]);
    }

    public function updated(Model $model): void
    {
        $new_values = $model->getChanges();

        $old_values = [];

        foreach ($new_values as $dirtyKey => $dirtyValue) {
            $old_values[$dirtyKey] = $model->getOriginal($dirtyKey);
        }

        Auditor::run([
            'action'      => AuditActionEnum::UPDATED,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => [
                'old_values' => $old_values,
                'new_values' => $new_values,
            ]
        ]);
    }

    public function deleted(Model $model): void
    {
        $details = $model->isForceDeleting() ? $model->toArray() : [];

        Auditor::run([
            'action'      => AuditActionEnum::DELETED,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => $details,
        ]);
    }

    public function restored(Model $model): void
    {
        Auditor::run([
            'action'      => AuditActionEnum::RESTORED,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => [],
        ]);
    }

    public function forceDeleted(Model $model): void
    {
        Auditor::run([
            'action'      => AuditActionEnum::FORCE_DELETED,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => $model->toArray(),
        ]);
    }

    public function morphToManyAttached(Model $model, $relation, $parent, $attributes): void
    {
        Auditor::run([
            'action'      => AuditActionEnum::ATTACHED,
            'causer_id'   => $parent->getKey(),
            'causer_type' => get_class($parent),
            'details'     => [
                'relation'   => $relation,
                'attributes' => $attributes
            ],
        ]);
    }

    public function morphToManyDetached(Model $model, $relation, $parent, $attributes): void
    {
        Auditor::run([
            'action'      => AuditActionEnum::DETACHED,
            'causer_id'   => $parent->getKey(),
            'causer_type' => get_class($parent),
            'details'     => [
                'relation'   => $relation,
                'attributes' => $attributes
            ],
        ]);
    }
}
