<?php

namespace App\Data\Enums;

/**
 * Defines the sending methods constasts available in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Custom-Attributes-Form
 */
enum SendingMethod: string
{
    case PARCEL_LOCKER = 'parcel_locker';
    case POK = 'pok';
    case POP = 'pop';
    case COURIER_POK = 'courier_pok';
    case BRANCH = 'branch';
    case DISPATCH_ORDER = 'dispatch_order';
}