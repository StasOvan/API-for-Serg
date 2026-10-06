<?php

namespace API\V1;

// Класс без состояния — можно вызывать статически.

class Transliterate
{
    /**
     * Карта соответствия: русская буква (в нижнем регистре) => латиница.
     * Схема близка к ГОСТ 7.79-2000 / BGN-PCGN.
     */
    private const MAP = [
        'а' => 'a',   'б' => 'b',   'в' => 'v',   'г' => 'g',   'д' => 'd',
        'е' => 'e',   'ё' => 'e',   'ж' => 'zh',  'з' => 'z',   'и' => 'i',
        'й' => 'y',   'к' => 'k',   'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',   'с' => 's',   'т' => 't',
        'у' => 'u',   'ф' => 'f',   'х' => 'kh',  'ц' => 'ts',  'ч' => 'ch',
        'ш' => 'sh',  'щ' => 'shch', 'ъ' => '',   'ы' => 'y',   'ь' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
    ];

    /**
     * Транслитерирует строку.
     *
     * Регистр первого символа сохраняется: если исходная строка
     * начиналась с заглавной буквы, результат тоже будет с заглавной.
     *
     * @param string $text Входная строка (например, "Александр")
     * @return string Результат (например, "Aleksandr")
     */
    
    public static function convert(string $text): string
    {
        if ($text === '') {
            return '';
        }

        // Запоминаем: была ли первая буква заглавной
        $firstChar = mb_substr($text, 0, 1, 'UTF-8');
        $firstWasUpper = ($firstChar === mb_strtoupper($firstChar, 'UTF-8'))
                      && ($firstChar !== mb_strtolower($firstChar, 'UTF-8'));

        $lower = mb_strtolower($text, 'UTF-8');
        $result = '';
        $length = mb_strlen($lower, 'UTF-8');

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($lower, $i, 1, 'UTF-8');
            $result .= self::MAP[$char] ?? $char;
        }

        // Возвращаем заглавную первую букву, если она была
        if ($firstWasUpper && $result !== '') {
            $result = mb_strtoupper(mb_substr($result, 0, 1, 'UTF-8'), 'UTF-8')
                    . mb_substr($result, 1, null, 'UTF-8');
        }

        return $result;
    }
}