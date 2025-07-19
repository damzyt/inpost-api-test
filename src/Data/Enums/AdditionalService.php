<?php

namespace App\Data\Enums;

/**
 * Represents additional services as defined in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#Us%C5%82ugi-dodatkowe.1
 * 
 * Enum is immutable
 */
enum AdditionalService: string
{
    case INSURANCE = 'insurance';
    case COD = 'cod';
    case SMS = 'sms';
    case EMAIL = 'email';
    case SATURDAY = 'saturday';
    case EVENING_DELIVERY = 'dor1720';
    case FOR_HOUR_9 = 'forhour_9';
    case FOR_HOUR_10 = 'forhour_10';
    case FOR_HOUR_11 = 'forhour_11';
    case FOR_HOUR_12 = 'forhour_12';
    case FOR_HOUR_13 = 'forhour_13';
    case FOR_HOUR_14 = 'forhour_14';
    case FOR_HOUR_15 = 'forhour_15';
    case FOR_HOUR_16 = 'forhour_16';
    case FOR_HOUR_17 = 'forhour_17';
    case RETURN_DOCUMENTS = 'rod';
}