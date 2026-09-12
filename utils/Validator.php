<?php

class Validator {
    private static $errors = [];

    public static function validate($data, $rules) {
        self::$errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $fieldRules);

            foreach ($rulesArray as $rule) {
                self::checkRule($field, $value, $rule);
            }
        }

        return empty(self::$errors);
    }

    private static function checkRule($field, $value, $rule) {
        if (strpos($rule, ':') !== false) {
            list($ruleName, $param) = explode(':', $rule);
        } else {
            $ruleName = $rule;
            $param = null;
        }

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    self::$errors[$field][] = "$field é obrigatório";
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    self::$errors[$field][] = "$field deve ser um email válido";
                }
                break;

            case 'min':
                if (!empty($value) && strlen((string)$value) < (int)$param) {
                    self::$errors[$field][] = "$field deve ter no mínimo $param caracteres";
                }
                break;

            case 'max':
                if (!empty($value) && strlen((string)$value) > (int)$param) {
                    self::$errors[$field][] = "$field deve ter no máximo $param caracteres";
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    self::$errors[$field][] = "$field deve ser um número";
                }
                break;

            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    self::$errors[$field][] = "$field deve ser uma data válida";
                }
                break;

            case 'in':
                $options = explode(',', $param);
                if (!empty($value) && !in_array($value, $options)) {
                    self::$errors[$field][] = "$field deve ser um dos seguintes: " . implode(', ', $options);
                }
                break;
        }
    }

    public static function getErrors() {
        return self::$errors;
    }
}
