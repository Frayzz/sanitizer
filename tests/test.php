<?php
require '../src/Sanitizer/Sanitizer.php';

use Sanitizer\Sanitizer\Sanitizer;

$jsonGetData = '{"foo": "1223", "bar": "asd", "baz": "+7 (707) 288-56-21", "qux": "1.7", "nested_array": [1, 5], "structure": { "key1": "55", "key2": "test" } }';
$specification = [
    'foo' => [
        'type' => 'integer',
        'element' => 'integer'
    ],
    'bar' => [
        'type' => 'string',
        'element' => 'string'
    ],
    'baz' => [
        'type' => 'phoneNumber',
        'element' => 'phoneNumber'
    ],
    'qux' => [
        'type' => 'float',
        'element' => 'float'
    ],
    'nested_array' => [
        'type' => 'array',
        'element' => 'integer'
    ],
    'structure' => [
        'type' => 'structure',
        'element' => [
            'key1' => 'integer',
            'key2' => 'string',
        ]
    ],
];



$jsonEncodeData = json_decode($jsonGetData, true);


$sanitize = new Sanitizer($specification);

$dataValue = $sanitize->validate($jsonEncodeData);

echo '<pre>';
print_r($dataValue);
echo '</pre>';