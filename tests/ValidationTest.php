<?php

namespace Tootootltd\AzureTextAnalytics\Tests;

use Faker\Factory;
use Tootootltd\AzureTextAnalytics\AzureTextAnalytics;
use Tootootltd\AzureTextAnalytics\Exceptions\ExceededApiLimit;
use Tootootltd\AzureTextAnalytics\Exceptions\InvalidDataStructure;

class ValidationTest extends TestCase
{
    /** @test */
    public function it_fails_validation_when_any_required_fields_are_missing()
    {
        $data = [
            [
                'id' => 1,
                // 'text' is missing
            ],
        ];

        $this->expectException(InvalidDataStructure::class);

        $analyse = new AzureTextAnalytics($data);
    }

    /** @test */
    public function it_fails_validation_if_the_data_is_in_the_wrong_format()
    {
        $data = [
            [
                'id' => 1,
                'text' => 'example',
            ],
            [
                'id' => 2,
                'text' => 'example 2',
            ],
            'text' => 'Example 3',
            [
                'id' => 4,
                'text' => 'example 4',
            ],
        ];

        $this->expectException(InvalidDataStructure::class);

        $analyse = new AzureTextAnalytics($data);
    }

    /** @test */
    public function it_fails_validation_if_any_document_is_longer_than_the_document_limit()
    {
        $faker = Factory::create();

        $data = [
            [
                'id' => 1,
                'text' => $faker->text(6000),
            ],
        ];

        $this->expectException(ExceededApiLimit::class);

        $analyse = new AzureTextAnalytics($data);
    }

    /** @test */
    public function it_fails_validation_when_document_count_is_exceeded()
    {
        $faker = Factory::create();

        $data = [];

        for ($i = 1; $i <= 1001; $i++) {
            $data[] = [
                'id' => $i,
                'text' => $faker->sentence,
            ];
        }

        $this->expectException(ExceededApiLimit::class);

        $analyse = new AzureTextAnalytics($data);
    }
}
