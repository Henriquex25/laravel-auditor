<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor\Enums;

use App\Enums\EnumResourcesTrait;

enum AuditAction: string
{
    case CREATED       = 'created';
    case UPDATED       = 'updated';
    case DELETED       = 'deleted';
    case RESTORED      = 'restored';
    case FORCE_DELETED = 'force_deleted';
    case ATTACHED      = 'attached';
    case DETACHED      = 'detached';
}