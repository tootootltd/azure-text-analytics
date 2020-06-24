<?php

namespace Tootootltd\AzureTextAnalytics\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tootootltd\AzureTextAnalytics\AzureTextAnalyticsServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [AzureTextAnalyticsServiceProvider::class];
    }

    protected function setUp() : void
    {
    	parent::setUp();

    	config([
    		'azure-text-analytics.endpoint' => 'https://example.com',
    		'azure-text-analytics.key' => 'example'
    	]);
    }
}
