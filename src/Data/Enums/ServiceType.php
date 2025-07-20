<?php

declare(strict_types=1);

namespace App\Data\Enums;

/**
 * Defines the service types constants available in the InPost API.
 * 
 * Enum containing all available service types for InPost shipments,
 * including both Paczkomat® and Courier service options.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#InPost-Paczkomat%C2%AE
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#InPost-Kurier
 * @package App\Data\Enums
 */
enum ServiceType: string
{   
    // InPost Paczkomat® service types
    case INPOST_LOCKER_STANDARD = 'inpost_locker_standard';
    case INPOST_LOCKER_ALLEGRO = 'inpost_locker_allegro';
    case INPOST_LOCKER_PASS_THRU = 'inpost_locker_pass_thru';
    case INPOST_LETTER_ALLEGRO = 'inpost_letter_allegro';
    case INPOST_COURIER_ALLEGRO = 'inpost_courier_allegro';
    case INPOST_COURIER_C2C = 'inpost_courier_c2c';
    // InPost Courier service types
    case INPOST_COURIER_STANDARD = 'inpost_courier_standard';
    case INPOST_COURIER_EXPRESS_1000 = 'inpost_courier_express_1000';
    case INPOST_COURIER_EXPRESS_1200 = 'inpost_courier_express_1200';
    case INPOST_COURIER_EXPRESS_1700 = 'inpost_courier_express_1700';
    case INPOST_COURIER_PALETTE = 'inpost_courier_palette';
    case INPOST_COURIER_ALCOHOL = 'inpost_courier_alcohol';
}
