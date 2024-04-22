<?php

namespace Henriquex25\LaravelAuditor\Observers;

use Illuminate\Database\Eloquent\Model;
use Henriquex25\LaravelAuditor\Facades\Auditor;
use Henriquex25\LaravelAuditor\Enums\AuditAction;

class AuditableObserver
{
    public $afterCommit = false;

    public function created(Model $model)
    {
        $data = $model->toArray();

        Auditor::run([
            'action'         => AuditAction::CREATED,
            'auditable_id'   => $model->id,
            'auditable_type' => get_class($model),
            'details'        => $data
        ]);
    }

    public function updated(Model $model)
    {
        $new_values = $model->getChanges();

        $old_values = [];

        foreach ($new_values as $dirtyKey => $dirtyValue) {
            $old_values[$dirtyKey] = $model->getOriginal($dirtyKey);
        }

        Auditor::run([
            'action'         => AuditAction::UPDATED,
            'auditable_id'   => $model->id,
            'auditable_type' => get_class($model),
            'details'        => [
                'old_value'  => $old_values,
                'new_values' => $new_values,
            ]
        ]);
    }

    public function deleted(Model $model)
    {
        Auditor::run([
            'action'         => AuditAction::DELETED,
            'auditable_id'   => $model->id,
            'auditable_type' => get_class($model),
            'details'        => $model->toArray(),
        ]);
    }

    public function restored(Model $model)
    {
        Auditor::run([
            'action'         => AuditAction::DELETED,
            'auditable_id'   => $model->id,
            'auditable_type' => get_class($model),
            'details'        => [],
        ]);
    }

    public function forceDeleted(Model $model)
    {
        Auditor::run([
            'action'         => AuditAction::DELETED,
            'auditable_id'   => $model->id,
            'auditable_type' => get_class($model),
            'details'        => $model->toArray(),
        ]);
    }
}