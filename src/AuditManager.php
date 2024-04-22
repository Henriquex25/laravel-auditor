<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Henriquex25\LaravelAuditor\Jobs\SaveAuditJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

class AuditManager
{
    /**
     * Dispatch a job to save the audit
     *
     * @param array $data
     * @return self
     */
    public function run(array $data): self
    {
        $data['user_id'] = Auth::id();
        $data['when']    = Carbon::now();
        $data['ip']      = Request::ip();

        if ($this->shouldQueue()) {
            $connection = $this->getConnection();
            $queue = $this->getQueue();

            $job = (new SaveAuditJob($data))
                ->onConnection($connection)
                ->onQueue($queue);

            Bus::dispatch($job);
        } else {
            Bus::dispatchSync(new SaveAuditJob($data));
        }

        return $this;
    }

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
}