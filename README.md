# Trafaret
A simple solution form markup validation.

## Installation
`composer require chi-teck/trafaret --dev`

## Usage
```php
<?php

use Trafaret\Config;
use Trafaret\Validator\ConstraintValidatorList;
use Trafaret\Validator;

$trafaret = <<< 'HTML'
  <h1>Example</h1>
  <div>
    <time>{% matches /\d\d:\d\d/ %}</time>
    <span class="total">{% > 10 %}</span>
  </div>
HTML;

$input = <<< 'HTML'
  <h1>Example</h1>
  <div>
    <time>15:30</time>
    <span class="total">15</span>
  </div>
HTML;

$validator = new Validator(
    Config::createDefault(),
    ConstraintValidatorList::createDefault(),
);

$violations = $validator->validate($input, $trafaret);
```

## License
MIT License
 