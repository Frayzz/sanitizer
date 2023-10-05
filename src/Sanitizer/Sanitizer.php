<?php
namespace Sanitizer\Sanitizer;
class Sanitizer
{
    private array $errors = [];
    private array $specification = [];

    public function __construct($specification)
    {
        $this->specification = $specification;
    }

    # Начало списка функций для валидации данных
    function validateInteger($jsonData, $key) {
        if (!ctype_digit(strval($jsonData))) {
            $this->errors['errors'][$key]['enterVal'] = $jsonData;
            $this->errors['errors'][$key]['errorType'] = 'Поле должно содержать только числа';
        }
    }
    function validateString($jsonData, $key) {
        if (preg_match('/\d/', $jsonData)) {
            $this->errors['errors'][$key]['enterVal'] = $jsonData;
            $this->errors['errors'][$key]['errorType'] = 'Поле должно содержать только строковое значение';
        }
    }
    function validatePhoneNumber($jsonData, $key) {
        # Функция разделена на два оператора IF для того что бы отображать разные ошибки по мере критичности
        $phoneNumber = preg_replace('/\D/', '', $jsonData);

        if (!preg_match('/^(?:\+7|7|8)[0-9]{10}$/', $phoneNumber)) {
            $this->errors['errors'][$key]['enterVal'] = $jsonData;
            $this->errors['errors'][$key]['errorType'] = 'Код страны не совпадает с Казахстан';
        }

        if (strlen($phoneNumber) !== 11) {
            $this->errors['errors'][$key]['enterVal'] = $jsonData;
            $this->errors['errors'][$key]['errorType'] = 'Номер должен содержать 11 цифр';
        }
    }
    function validateFloat($jsonData, $key) {
        if (is_numeric($jsonData) && !strpos($jsonData, '.') !== false) {
            $this->errors['errors'][$key]['enterVal'] = $jsonData;
            $this->errors['errors'][$key]['errorType'] = 'Это поле должно содержать число с плавающщей точкой';
        }
    }
    function validateArrayInteger($jsonData, $key, $type) {
        foreach ($jsonData as $id => $element) {
            if (gettype($element) !== $type) {
                $this->errors['errors'][$key]['enterVal'] = $jsonData;
                $this->errors['errors'][$key]['errorType'] = 'Массив не является одним фиксированным типом';
                $this->errors['errors'][$key]['type'] = gettype($element);

                break;
            }
        }
    }

    function validateStructure($jsonData, $types) {
        foreach ($jsonData as $key => $element) {
            if (gettype($element) !== $types[$key]) {
                $this->errors['errors'][$key]['enterVal'] = $jsonData;
                $this->errors['errors'][$key]['errorType'] = 'Массив не является одним фиксированным типом';
                $this->errors['errors'][$key]['type'] = gettype($element);
            }
        }
    }
    # Конец списка функций для валидации данных

    # Определение типа данных и выборка вызова нужных функций для сверки правльности типов
    function validate($jsonGetData) {
        foreach ($jsonGetData as $key => $jsonData ) {
            switch ($this->specification[$key]['type']) {
                case 'integer':
                    $this->validateInteger($jsonData, $key);
                    break;
                case 'string':
                    $this->validateString($jsonData, $key);
                    break;
                case 'phoneNumber':
                    $this->validatePhoneNumber($jsonData, $key);
                    break;
                case 'float':
                    $this->validateFloat($jsonData, $key);
                    break;
                case 'array':
                    $this->validateArrayInteger($jsonData, $key, $this->specification[$key]['element']);
                    break;
                case 'structure':
                    $this->validateStructure($jsonData, $this->specification[$key]['element']);
                    break;
            }
        }

        # Конечный ответ, проверка пустой ли массив с ошибками, если пустой то направляется успешный ответ
        if (empty($this->errors)) {
            return $data["message"] = "Success";
        } else {
            return $this->errors;
        }

    }
}


