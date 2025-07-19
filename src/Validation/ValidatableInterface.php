<?php

namespace App\Validation;

use InvalidArgumentException;

interface ValidatableInterface
{
    public function validate(): void;
}