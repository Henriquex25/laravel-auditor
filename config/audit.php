<?php

return [
    'enabled' => env('AUDITING_ENABLED', true),

    'audit_in_console' => false,

    'should_queue' => true,

    'connection' => 'default',

    'queue' => 'default',

    'after_commit' => true,
];
