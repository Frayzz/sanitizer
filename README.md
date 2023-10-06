# sanitizer
 Library for validating incoming data. Test task from arbuz.kz.


 Для установки выполняем набор комманд
 ```
composer require frayzz/sanitizer:dev-main
```
Подключаем библиотеку
```
require_once "vendor/autoload.php";
use Sanitizer\Sanitizer\Sanitizer;
```
Пример использования
Код:
```
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
```
Ответ:
```
Array
(
    [errors] => Array
        (
            [key1] => Array
                (
                    [enterVal] => Array
                        (
                            [key1] => 55
                            [key2] => test
                        )

                    [errorType] => Массив не является одним фиксированным типом
                    [type] => string
                )

        )

)
```
