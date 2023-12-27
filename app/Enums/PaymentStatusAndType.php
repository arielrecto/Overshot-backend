<?php

namespace App\Enums;



enum PaymentStatusAndType : string {

    case PAID = 'Paid';
    case COD = 'COD';
    case UNPAID = 'Unpaid';
    case GCASH = 'Gcash';
}
