<?php

namespace Henriquex25\LaravelAuditor\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Clinicker\Tenant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Henriquex25\LaravelAuditor\Model\Audit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveAuditJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected array $data,
    ) {
        //
    }

    public function handle()
    {
        Audit::create($this->data);
    }
}