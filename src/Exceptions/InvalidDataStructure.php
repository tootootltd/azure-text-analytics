<?php

namespace Tootootltd\AzureTextAnalytics\Exceptions;

use Exception;

class InvalidDataStructure extends Exception
{
    public static function missingKeys(array $requiredFields): self
    {
        return new static('Your data must contain all required fields: '.implode(', ', $requiredFields).'.');
    }

    public static function wrongFormat(): self
    {
        return new static('Your data must be a multi dimensional array, with each child array containing all required fields as their keys.');
    }
}
