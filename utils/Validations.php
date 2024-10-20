<?php

class Validations
{
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateText(string $text, int $minLength = 1, int $maxLength = 255, $messages): array
    {
        $cleanedText = strip_tags($text);

        if (empty($cleanedText)) {
            return ["success" => false, "error" => $messages["text_is_empty"]];
        }

        if (strlen($cleanedText) < $minLength) {
            return ["success" => false, "error" => $messages["text_too_short"]];
        }

        if (strlen($cleanedText) > $maxLength) {
            return ["success" => false, "error" => $messages["text_too_long"]];
        }

        return ["success" => true, "data" => $cleanedText];
    }
    
    public static function removeNullFields(array $data): array
    {
        return array_filter($data, function ($value) {
            return !is_null($value);
        });
    }
}
