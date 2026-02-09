<?php

namespace App\Support;

class InputSanitizer
{
    public static function clean(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $clean = strip_tags($value);
        $clean = trim($clean);

        return $clean === '' ? null : $clean;
    }
}
