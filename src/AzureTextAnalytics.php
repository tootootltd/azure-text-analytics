<?php

namespace Tootootltd\AzureTextAnalytics;

use Illuminate\Support\Facades\Http;
use Tootootltd\AzureTextAnalytics\Exceptions\ExceededApiLimit;
use Tootootltd\AzureTextAnalytics\Exceptions\InvalidDataStructure;

class AzureTextAnalytics
{
    const API_VERSION = 'v3.0';

    // https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/overview#data-limits
    const DOCUMENT_LENGTH_LIMIT = 5120;
    const DOCUMENT_LIMIT = 1000;

    // https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-call-api#json-schema-definition
    const REQUIRED_FIELDS = [
        'id', 'text',
    ];

    protected string $endpoint;

    protected string $key;

    protected array $headers;

    protected string $path;

    protected array $requestBody = [];

    /**
     * @param mixed  $data  Can be a single string, or an array containing the keys `id` and `text`,
     */
    public function __construct($data)
    {
        $this->validateAndSetData($data);

        $this->setConfig(config('azure-text-analytics.endpoint'),
                         config('azure-text-analytics.key'));
    }

    public function sentimentAnalysis()
    {
        return $this->makeRequest('/sentiment');
    }

    public function keyPhrases()
    {
        return $this->makeRequest('/keyPhrases');
    }

    public function detectLanguage()
    {
        return $this->makeRequest('/languages');
    }

    public function namedEntityRecognition()
    {
        return $this->makeRequest('/entities/recognition/general');
    }

    public function entityLinking()
    {
        return $this->makeRequest('/entities/linking');
    }

    private function makeRequest(string $path)
    {
        $this->setPath($path);

        return Http::withHeaders($this->headers)
                         ->post($this->endpoint.$this->path, $this->requestBody);
    }

    private function setPath(string $path): self
    {
        $this->path = '/text/analytics/'.self::API_VERSION.$path;

        return $this;
    }

    private function validateAndSetData($data): void
    {
        // If the user just supplied a string, convert it to an array with an ID of 1
        if (is_string($data)) {
            $data = [
                [
                    'id' => 1,
                    'text' => $data,
                ],
            ];
        }

        // If the user just supplied an array, wrap it in a parent array
        if (! is_array(end($data))) {
            $data = [
                $data,
            ];
        }

        // Validate that child arrays contain all required fields
        //  e.g...
        // [
        // 		[
        // 			'id' => 1,
        // 			'text' => 'foo'
        // 		],
        // 		...
        // ]
        foreach ($data as $array) {
            if (! is_array($array)) {
                throw InvalidDataStructure::wrongFormat();
            }

            $this->validateArrayKeys($array);
        }

        $this->validateDocumentLimit($data);

        // Arrays are valid, build the request body.
        $this->requestBody = [
            'documents' => $data,
        ];
    }

    private function validateArrayKeys(array $data): void
    {
        if (count(array_intersect_key(array_flip(self::REQUIRED_FIELDS), $data)) < count(self::REQUIRED_FIELDS)) {
            throw InvalidDataStructure::missingKeys(self::REQUIRED_FIELDS);
        }

        // Validate each array's document (text) length
        $length = strlen($data['text']);
        if ($length > self::DOCUMENT_LENGTH_LIMIT) {
            throw ExceededApiLimit::documentLength($length, self::DOCUMENT_LENGTH_LIMIT);
        }
    }

    private function validateDocumentLimit(array $documents): void
    {
        $count = count($documents);
        if ($count > self::DOCUMENT_LIMIT) {
            throw ExceededApiLimit::documentCount($count, self::DOCUMENT_LIMIT);
        }
    }

    private function setConfig(string $endpoint, string $key): void
    {
        $this->endpoint = $endpoint;

        $this->key = $key;

        $this->headers = [
            'Content-type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $this->key,
        ];
    }
}
