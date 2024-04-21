<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor\Facades;

use Illuminate\Support\Facades\Facade;
use Henriquex25\LaravelAuditor\AuditManager;

class Auditor extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AuditManager::class;
    }
}