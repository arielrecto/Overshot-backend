<?php



namespace App\Enums;



enum DeliveryStatus : string {

    case PENDING = 'pending';
    case ON_DELIVER = 'For Delivery';
    case DONE = 'done';
}
