<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Henriquex25\LaravelAuditor\Jobs\SaveAuditJob;
use Illuminate\Support\Facades\Bus;

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

        Bus::dispatch(new SaveAuditJob($data));

        return $this;
    }
}