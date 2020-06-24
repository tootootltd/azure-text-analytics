<?php

namespace Tootootltd\AzureTextAnalytics\Tests;

use Faker\Factory;
use Illuminate\Support\Facades\Http;
use Tootootltd\AzureTextAnalytics\AzureTextAnalytics;
use Tootootltd\AzureTextAnalytics\AzureTextAnalyticsServiceProvider;
use Tootootltd\AzureTextAnalytics\Tests\TestCase;

class NamedEntityRecognitionTest extends TestCase
{
    /** @test */
    public function it_works_with_a_multi_dimensional_array()
    {
        $faker = Factory::create();

        $data = [
            [
                'id' => 1,
                'text' => $faker->realText
            ],
            [
                'id' => 2,
                'text' => $faker->realText
            ]
        ];

        $http = Http::fake([
            '*' => Http::response($this->successfulResponse(), 200),
        ]);

        $analyse = new AzureTextAnalytics($data);
        $response = $analyse->namedEntityRecognition();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/entities/recognition/general' &&
                   $request['documents'][0]['id'] == 1 &&
                   $request['documents'][1]['id'] == 2;
        });
    }

    /** @test */
    public function it_works_with_an_array()
    {
        $faker = Factory::create();

        $data = [
            'id' => 3,
            'text' => $faker->realText
        ];

        $http = Http::fake([
            '*' => Http::response($this->successfulResponse(), 200),
        ]);

        $analyse = new AzureTextAnalytics($data);
        $response = $analyse->namedEntityRecognition();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/entities/recognition/general' &&
                   $request['documents'][0]['id'] == 3;
        });
    }

    /** @test */
    public function it_works_with_a_string()
    {
        $faker = Factory::create();

        $data = 'my string';

        $http = Http::fake([
            '*' => Http::response($this->successfulResponse(), 200),
        ]);

        $analyse = new AzureTextAnalytics($data);
        $response = $analyse->namedEntityRecognition();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/entities/recognition/general' &&
                   $request['documents'][0]['text'] == 'my string';
        });
    }

    protected function successfulResponse()
    {
        return '
            {
              "documents": [
                {
                  "id": "1",
                  "entities": [
                    {
                      "text": "Kieran",
                      "category": "Person",
                      "offset": 10,
                      "length": 6,
                      "confidenceScore": 0.66
                    }
                  ],
                  "warnings": []
                }
              ],
              "errors": [],
              "modelVersion": "2020-04-01"
            }
        ';
    }
}
