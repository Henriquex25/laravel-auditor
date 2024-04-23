<?php

namespace Henriquex25\LaravelAuditor\Observers;

use Henriquex25\LaravelAuditor\Enums\AuditActionEnum;
use Henriquex25\LaravelAuditor\Facades\Auditor;
use Illuminate\Database\Eloquent\Model;

class AuditableObserver
{
    public $afterCommit = true;

    public function created(Model $model): void
    {
        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::CREATED,
            details: []
        );

        Auditor::run($model, $data);
    }

    public function updated(Model $model): void
    {
        $new_values = $model->getChanges();
        $old_values = [];

        foreach ($new_values as $dirtyKey => $dirtyValue) {
            $old_values[$dirtyKey] = $model->getOriginal($dirtyKey);
        }

        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::UPDATED,
            details: [
                'old_values' => $old_values,
                'new_values' => $new_values,
            ]
        );

        Auditor::run($model, $data);
    }

    public function deleted(Model $model): void
    {
        $details = $model->isForceDeleting() ? $model->toArray() : [];

        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::DELETED,
            details: $details
        );

        Auditor::run($model, $data);
    }

    public function restored(Model $model): void
    {
        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::RESTORED,
            details: []
        );

        Auditor::run($model, $data);
    }

    public function forceDeleted(Model $model): void
    {
        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::FORCE_DELETED,
            details: $model->toArray()
        );

        Auditor::run($model, $data);
    }

    public function morphToManyAttached(Model $model, $relation, $parent, $attributes): void
    {
        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::ATTACHED,
            details: [
                'relation'   => $relation,
                'attributes' => $attributes
            ]
        );

        Auditor::run($model, $data);
    }

    public function morphToManyDetached(Model $model, $relation, $parent, $attributes): void
    {
        $data = $this->getData(
            model: $model,
            action: AuditActionEnum::DETACHED,
            details: [
                'relation'   => $relation,
                'attributes' => $attributes
            ]
        );

        Auditor::run($model, $data);
    }

    protected function getData(Model $model, AuditActionEnum $action, array $details): array
    {
        return [
            'action'      => $action,
            'causer_id'   => $model->getKey(),
            'causer_type' => get_class($model),
            'details'     => $details
        ];
    }
}
