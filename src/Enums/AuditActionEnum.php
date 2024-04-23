<?php

declare(strict_types = 1);

namespace Henriquex25\LaravelAuditor\Enums;

enum AuditActionEnum: string
{
    case CREATED         = 'created';
    case HAS_ONE_CREATED = 'has_one_created';
    case UPDATED         = 'updated';
    case DELETED         = 'deleted';
    case RESTORED        = 'restored';
    case FORCE_DELETED   = 'force_deleted';
    case ATTACHED        = 'attached';
    case DETACHED        = 'detached';
}
