<?php

namespace App\Data\Enums;

/**
 * Represents the service category as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#InPost-Paczkomat%C2%AE
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#InPost-Kurier
 * 
 * Enum is immutable
 */

enum ServiceCategory
{
    case Locker;
    case Courier;
}