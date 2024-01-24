<?php

namespace App\Enums;


enum OrderStatusEnum : string {

    case PENDING = 'pending';
    case PROCESSED = 'processed';
    case DONE = 'done';
}
