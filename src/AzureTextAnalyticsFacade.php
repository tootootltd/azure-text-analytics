<?php

namespace Tootootltd\AzureTextAnalytics;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tootootltd\AzureTextAnalytics\Skeleton\SkeletonClass
 */
class AzureTextAnalyticsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'azure-text-analytics';
    }
}
