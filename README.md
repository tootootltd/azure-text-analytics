# PHP Azure Text Analytics
	
[![Latest Version on Packagist](https://img.shields.io/packagist/v/tootootltd/azure-text-analytics.svg?style=flat-square)](https://packagist.org/packages/tootootltd/azure-text-analytics)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![run-tests](https://github.com/tootootltd/azure-text-analytics/workflows/run-tests/badge.svg)](https://github.com/spatie/browsershot/actions)

A very simple wrapper around version 3.0 of Azure Cognitive Services' Text Analytics API: https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/
	
## Installation
	
You can install the package via composer:
	
```bash
composer require tootootltd/azure-text-analytics
```
	
Publish the config file and put your Azure Cognitive Services endpoint and key in your env file.
	
```bash
php artisan vendor:publish --provider="Tootootltd\AzureTextAnalytics\AzureTextAnalyticsServiceProvider"
```

## Requirements

1. An Azure Cognitive Services endpoint and key.
2. PHP 7.4

## Usage
	
This package supports all 5 Text Analytics endpoints and each return the full raw response body.
	
You can pass your text into the constructor in a few different formats:
	
**String**
	
	
```php
$myText = 'Example';
```
	
**String and ID**
	
	
```php
$myText = [
	'id' => 1,
	'text' => 'Example'
];
```
	
**Multiple strings and ID's**
	
	
```php
$myText = [
	[
		'id' => 1,
		'text' => 'Example one'
	],
	[
		'id' => 2,
		'text' => 'Example two'
	],
	[
		'id' => 3,
		'text' => 'Example three'
	]
];
```
	
Just pass any of these into the constructor.
	
```php
$text = new AzureTextAnalytics($myText)
```
	
This package will do a bit of validation on your text before hitting Azure's API, such as;
	
1. Checking the length of each `document` (string of text) and the number of `documents` per request to ensure they aren't above the Azure API's limits (`5,120` characters and `1,000` documents at time of writing respectively). In both these instances an `ExceededApiLimit` exception will be thrown. More info on these limits can be found on [Azure's documentation](https://learn.microsoft.com/en-gb/azure/cognitive-services/language-service/concepts/data-limits#maximum-documents-per-request).
2. Ensuring that the required fields are present when passing an array (`id` and `text` at time of writing). More info on these can be found on [Azure's documentation](https://learn.microsoft.com/en-us/azure/cognitive-services/language-service/).
	
	
### Methods:
	
**Sentiment Analysis** - View [example response](https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-sentiment-analysis?tabs=version-3#view-the-results)
	
```php
$text = new AzureTextAnalytics($myText)
$text->sentimentAnalsis();
```
	
**Key Phrases** - View [example response](https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-keyword-extraction#step-3-view-results)
	
```php
$text = new AzureTextAnalytics($myText)
$text->keyPhrases();
```
	
**Language Detection** - View [example response](https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-language-detection#step-3-view-the-results)
	
```php
$text = new AzureTextAnalytics($myText)
$text->detectLanguage();
```
	
**Named Entity Recognition** - View [example response](https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-entity-linking?tabs=version-3#example-ner-response)
	
```php
$text = new AzureTextAnalytics($myText)
$text->namedEntityRecognition();
```
	
**Entity Linking** - View [example response](https://docs.microsoft.com/en-gb/azure/cognitive-services/text-analytics/how-tos/text-analytics-how-to-entity-linking?tabs=version-3#example-entity-linking-response)
	
```php
$text = new AzureTextAnalytics($myText)
$text->entityLinking();
```
	
	
## Testing
	
``` bash
composer test
```
	
## Changelog
	
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
	
## Contributing
	
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.
	
	
## License
	
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
