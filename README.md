# Trafaret

[![Build Status](https://travis-ci.org/Chi-teck/trafaret.svg?branch=master)](https://travis-ci.org/Chi-teck/trafaret)

A simple way to validate textual data.

## Installation
`composer require chi-teck/trafaret --dev`

## Usage
```php
<?php

use Trafaret\Trafaret;
use Trafaret\Validator;

include __DIR__ . '/../vendor/autoload.php';

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
        <span class="total">{% value == total %}</span>
    </div>
    HTML,
    ['total' => 15],
);

$validator = Validator::createDefault();

$violations = $validator->validate($input, $trafaret);
```

## License
MIT License
 
