# Trafaret
A simple way to extract data from multiline textual snippets.

## Installation
`composer require chi-teck/trafaret --dev`

## Usage
```php
$input = <<< 'HTML'
    <h1>Example</h1> 
    <div>
        <time>12:08</time>
        <span class="total">15</span>
    </div>
    HTML;

$trafaret = new Trafaret(
    <<< 'HTML'
        <h1>Example</h1>
        <div>
            <time>{{ time }}</time>
            <span class="total">{{ total }}</span>
        </div>
        HTML,
    [
        'time' => new Regex('/^\d\d:\d\d$/'),
        'total' => new GreaterThan(10),
    ],
);

$manager = Manager::createDefault();

try {
    $data = $manager->apply($trafaret, $input);
}
catch (ExceptionInterface $exception) {
    \file_put_contents('php://stderr', $exception->getMessage() . "\n");
    exit(1);
}

print_r($data);
```
Placeholders are validated using [Symfony Validator](https://symfony.com/doc/current/components/validator.html).


For tests based on PHPUnit you can make use of TrafaretTrait to validate markup as shown below.
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

## License
MIT License
 
