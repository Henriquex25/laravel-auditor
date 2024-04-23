<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor;

use Henriquex25\LaravelAuditor\Jobs\SaveAuditJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class AuditManager
{
    protected function shouldQueue(): bool
    {
        return Config::get('audit.should_queue');
    }

    protected function getConnection(): ?string
    {
        $auditConnection = Config::get('audit.connection');

        return $auditConnection !== 'default' ? $auditConnection : null;
    }

    protected function getQueue(): ?string
    {
        $auditQueue = Config::get('audit.queue');

        return $auditQueue !== 'default' ? $auditQueue : null;
    }

    protected function getSaveAuditJob(array $data, array $args): SaveAuditJob
    {
        $saveAuditJob = Config::get('audit.save_audit_job');

        return (new $saveAuditJob($data, ...$args));
    }

    protected function getArgs(Model $model): array
    {
        if (method_exists($model, 'extraSaveAuditJobArgs')) {
            return $model->getArgs();
        }

        return [];
    }

    public function run(Model $model, array $data): void
    {
        $loggedUser = Auth::user();

        if ($loggedUser && (empty($data['causer_type']) || empty($data['causer_id']))) {
            $data['causer_type'] = get_class($loggedUser);
            $data['causer_id']   = $loggedUser->getKey();
        }

        $data['when']       = Carbon::now();
        $data['ip_address'] = Request::ip();

        $args = $this->getArgs($model);
        $job  = $this->getSaveAuditJob($data, $args);

        if ($this->shouldQueue()) {
            $connection = $this->getConnection();
            $queue      = $this->getQueue();

            $job->onConnection($connection)
                ->onQueue($queue);

            Bus::dispatch($job);

            return;
        }

        Bus::dispatchSync($job);
    }
}
