<?php

namespace Tootootltd\AzureTextAnalytics\Exceptions;

use Exception;

class ExceededApiLimit extends Exception
{
    public static function documentLength(int $documentLength, int $documentLimit): self
    {
        return new static('The maximum size of a single document is '.$documentLimit.' characters, yours is '.$documentLength);
    }

    public static function documentCount(int $documentCount, int $documentLimit): self
    {
        return new static('The maximum number of documents in a request is '.$documentLimit.' characters, yours is '.$documentCount);
    }
}
