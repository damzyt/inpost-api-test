<?php

declare(strict_types=1);

namespace App\Data\Enums;

/**
 * Defines the sending methods constants available in the InPost API.
 * 
 * Enum containing different sending method options for shipments,
 * including parcel lockers, pickup points, and courier services.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731043/Walidacja+formularzy#Custom-Attributes-Form
 * @package App\Data\Enums
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