<?php 

declare(strict_types=1);

namespace App\Data\Enums;

/**
 * Defines the parcel templates constants available in the InPost API.
 * 
 * Enum containing predefined parcel size templates for standardized
 * shipment dimensions and weight specifications.
 * 
 * @see https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#Wymiary-i-wagi-przesy%C5%82ek
 * @package App\Data\Enums
 */
enum ParcelTemplate: string
{
    // STANDARD
    case SMALL = 'small';
    case MEDIUM = 'medium';
    case LARGE = 'large';
    case XLARGE = 'xlarge';
    // LETTERS
    case LETTER_A = 'letter_a';
    case LETTER_B = 'letter_b';
    case LETTER_C = 'letter_c';
}