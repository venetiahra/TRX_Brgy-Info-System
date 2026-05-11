<?php
class Validator
{
    public static function sanitize($value): string
    {
        return trim(strip_tags((string) $value));
    }

    public static function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::sanitizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = self::sanitize($value);
            }
        }
        return $data;
    }

    public static function validateRequired(array $data, array $fields): array
    {
        $errors = [];
        foreach ($fields as $field => $label) {
            if (trim((string) ($data[$field] ?? '')) === '') {
                $errors[$field] = $label . ' is required.';
            }
        }
        return $errors;
    }

    public static function nullableString($value): ?string
    {
        $value = self::sanitize($value);
        return $value === '' ? null : $value;
    }

    public static function integer($value, int $default = 0): int
    {
        return is_numeric($value) ? (int) $value : $default;
    }
}
?>
