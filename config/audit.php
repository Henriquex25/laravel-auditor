<?php

return [
    'enabled' => env('AUDITING_ENABLED', true),

    'audit_in_console' => false,

    'should_queue' => true,

    'connection' => 'default',

    'queue' => 'default',

    'save_audit_job' => Henriquex25\LaravelAuditor\Jobs\SaveAuditJob::class,
];
