<?php

header('Content-Type: text/plain; charset=utf-8');

/*
text/plain
по умолчанию: raw=false

/API/V1/GET/random-phone	+7 (947) 231-08-56
/API/V1/GET/random-phone?raw=true	+79472310856
/API/V1/GET/random-phone?raw=false	+7 (947) 231-08-56
*/

// Читает GET-параметр как boolean. Значение "true" (без регистра) -> true, всё остальное -> $default.
function getBoolParam(string $key, bool $default): bool
{
    if (!isset($_GET[$key])) 
        return $default;

    return strtolower((string)$_GET[$key]) === 'true';
}



//* --- Логика эндпоинта --- *//

$raw = getBoolParam('raw', false);

$prefix = random_int(901, 999);
$part1  = random_int(2, 999);
$part2  = random_int(0, 99);
$part3  = random_int(0, 99);

if ($raw) // +79472310856
    $phone = sprintf('+7%d%03d%02d%02d', $prefix, $part1, $part2, $part3);
else // +7 (947) 231-08-56
    $phone = sprintf('+7 (%d) %03d-%02d-%02d', $prefix, $part1, $part2, $part3);

// Возвращаем результат
echo $phone;