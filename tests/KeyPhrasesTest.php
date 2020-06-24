<?php

namespace Tootootltd\AzureTextAnalytics\Tests;

use Faker\Factory;
use Illuminate\Support\Facades\Http;
use Tootootltd\AzureTextAnalytics\AzureTextAnalytics;

class KeyPhrasesTest extends TestCase
{
    /** @test */
    public function it_works_with_a_multi_dimensional_array()
    {
        $faker = Factory::create();

        $data = [
            [
                'id' => 1,
                'text' => $faker->realText,
            ],
            [
                'id' => 2,
                'text' => $faker->realText,
            ],
        ];

        $http = Http::fake([
            '*' => Http::response($this->successfulResponse(), 200),
        ]);

        $analyse = new AzureTextAnalytics($data);
        $response = $analyse->keyPhrases();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/keyPhrases' &&
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
            'text' => $faker->realText,
        ];

        $http = Http::fake([
            '*' => Http::response($this->successfulResponse(), 200),
        ]);

        $analyse = new AzureTextAnalytics($data);
        $response = $analyse->keyPhrases();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/keyPhrases' &&
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
        $response = $analyse->keyPhrases();

        Http::assertSent(function ($request) {
            return $request->hasHeader('Content-type', 'application/json') &&
                   $request->hasHeader('Ocp-Apim-Subscription-Key', 'example') &&
                   $request->url() == config('azure-text-analytics.endpoint').'/text/analytics/v3.0/keyPhrases' &&
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
                  "keyPhrases": [
                    "life"
                  ],
                  "warnings": []
                }
              ],
              "errors": [],
              "modelVersion": "2019-10-01"
            }
        ';
    }
}
