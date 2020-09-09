# Trafaret

[![Build Status](https://travis-ci.org/Chi-teck/trafaret.svg?branch=master)](https://travis-ci.org/Chi-teck/trafaret)

A simple way to validate multiline textual data.

## Installation
`composer require chi-teck/trafaret --dev`

## Usage
```php
$input = <<< 'HTML'
    <h1>Example</h1>
    <div>
        <time>15:30</time>
        <span class="total">15</span>
    </div>
HTML;

$trafaret = new Trafaret(
    <<< 'HTML'
        <h1>Example</h1>
        <div>
            <time>{% value matches "/^[0-2][0-9]:[0-5][0-9]$/" %}</time>
            <span class="total">{% value == expected_total %}</span>
        </div>
    HTML,
    ['expected_total' => 15],
);

$validator = Validator::createDefault();

$violations = $validator->validate($input, $trafaret);
```

For PHPUnit based you can make use TrafaretTrait to set up validator as shown below.

```php
final class HomePageTest extends SiteTestCase
{
    use TrafaretTrait;
    
    public function testMarkup(): void
    {
        $trafaret = Trafaret::createFromFile(__DIR__ . '/../fixtures/home-page.html.trf');
        $actual_html = $this->findByXpath('//div[@class = "page"]')->getOuterHtml();
        $this->assertStringByTrafaret($trafaret, $actual_html);
    }
}

```
## Configuration

Instead of getting an instance of the validator from `::createDefault()` factory it is
recommended to configure it manually.

```php
$expression_language = new ExpressionLanguage(null, [new ExpressionFunctionProvider()]); 
$config = new Config([
    'ignore_leading_spaces' => true,
    'ignore_trailing_spaces' => false,
    'ignore_empty_lines' => true,
]);

$validator = new Validator($expression_language, $config);
```

## License
MIT License
 
