<?php

namespace App\Services;


class Helper
{
    /**
     * Очистка номера телефона от всех нецифровых символов, приведение к международному формату,
     *
     * @param string|null $phone
     * @return string|null
     */
    public static function cleanPhone(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        $phone = preg_replace('/\D/', '', $phone);

        return preg_replace('/^8/', '7', $phone);
    }
}
