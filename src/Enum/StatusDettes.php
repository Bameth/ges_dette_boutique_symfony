<?php

namespace App\Enum;

enum StatusDettes: string
{
    case PAYEE = 'payée';
    case NON_PAYEE = 'non payée';
}
