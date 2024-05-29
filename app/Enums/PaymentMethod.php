<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case ONLINE = 'online';
}