<?php



namespace App\Enums;



enum DeliveryStatus : string {

    case PENDING = 'pending';
    case ON_DELIVER = 'on_deliver';
    case DONE = 'done';
}
