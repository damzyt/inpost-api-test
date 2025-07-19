<?php 

namespace App\Data\Enums;

/**
 * Defines the parcel templates constants available in the InPost API.
 * https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/11731062/Rozmiary+i+us+ugi+dla+przesy+ek#Wymiary-i-wagi-przesy%C5%82ek
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