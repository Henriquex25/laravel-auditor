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
            'action'  => AuditActionEnum::CREATED,
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
            'action'  => AuditActionEnum::UPDATED,
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
            ? []
            : $model->toArray();

        $data = [
            'action'  => AuditActionEnum::DELETED,
            'details' => $details
        ];

        Auditor::run($model, $data);
    }

    public function restored(Model $model): void
    {
        $data = [
            'action'  => AuditActionEnum::RESTORED,
            'details' => []
        ];

        Auditor::run($model, $data);
    }

    public function forceDeleted(Model $model): void
    {
        $data = [
            'action'  => AuditActionEnum::FORCE_DELETED,
            'details' => $model->toArray()
        ];

        Auditor::run($model, $data);
    }

    public function morphToManyAttached(Model $model, $relation, $parent, $ids, $attributes): void
    {
        $data = [
            'action'  => AuditActionEnum::ATTACHED,
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
            'action'  => AuditActionEnum::DETACHED,
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
