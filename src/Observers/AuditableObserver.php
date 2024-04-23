<?php

namespace Henriquex25\LaravelAuditor\Observers;

use Henriquex25\LaravelAuditor\Enums\AuditActionEnum;
use Henriquex25\LaravelAuditor\Facades\Auditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditableObserver
{
    public $afterCommit = true;

    public function created(Model $model): void
    {
        $data = [
            'actions' => AuditActionEnum::CREATED,
            'details' => $model->toArray()
        ];

        Auditor::run($model, $data);
    }

    public function updated(Model $model): void
    {
        $new_values = $model->getChanges();
        $old_values = [];

        foreach ($new_values as $dirtyKey => $dirtyValue) {
            $old_values[$dirtyKey] = $model->getOriginal($dirtyKey);
        }

        $data = [
            'actions' => AuditActionEnum::UPDATED,
            'details' => [
                'old_values' => $old_values,
                'new_values' => $new_values,
            ]
        ];

        Auditor::run($model, $data);
    }

    public function deleted(Model $model): void
    {
        $details = in_array(SoftDeletes::class, class_uses($model))
            ? ['auditable_id' => $model->getKey()]
            : $model->toArray();

        $data = [
            'actions' => AuditActionEnum::DELETED,
            'details' => $details
        ];

        Auditor::run($model, $data);
    }

    public function restored(Model $model): void
    {
        $data = [
            'actions' => AuditActionEnum::RESTORED,
            'details' => ['auditable_id' => $model->getKey()]
        ];

        Auditor::run($model, $data);
    }

    public function forceDeleted(Model $model): void
    {
        $data = [
            'actions' => AuditActionEnum::FORCE_DELETED,
            'details' => $model->toArray()
        ];

        Auditor::run($model, $data);
    }

    public function morphToManyAttached(Model $model, $relation, $parent, $ids, $attributes): void
    {
        $data = [
            'actions' => AuditActionEnum::ATTACHED,
            'details' => [
                'parent'     => $parent,
                'relation'   => $relation,
                'ids'        => $ids,
                'attributes' => $attributes,
            ]
        ];

        Auditor::run($model, $data);
    }

    public function morphToManyDetached(Model $model, $relation, $parent, $ids, $attributes): void
    {
        $data = [
            'actions' => AuditActionEnum::DETACHED,
            'details' => [
                'parent'     => $parent,
                'relation'   => $relation,
                'ids'        => $ids,
                'attributes' => $attributes,
            ]
        ];

        Auditor::run($model, $data);
    }
}
