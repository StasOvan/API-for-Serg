<?php

header('Content-Type: text/plain; charset=utf-8');

require __DIR__ . '/../../Transliterate.php';

use API\V1\Transliterate;

/*
text/plain
по умолчанию: capitalize=true
по умолчанию: transliter=false

/API/V1/GET/random-city	Москва
/API/V1/GET/random-city?capitalize=false	москва
/API/V1/GET/random-city?transliter=true	Moskva
/API/V1/GET/random-city?capitalize=false&transliter=true	moskva
*/

$cities = [
    "Москва", "Санкт-Петербург", "Новосибирск", "Екатеринбург", "Казань",
    "Нижний Новгород", "Челябинск", "Самара", "Омск", "Ростов-на-Дону",
    "Уфа", "Красноярск", "Воронеж", "Пермь", "Волгоград",
    "Краснодар", "Саратов", "Тюмень", "Тольятти", "Ижевск",
    "Барнаул", "Ульяновск", "Иркутск", "Хабаровск", "Ярославль",
    "Владивосток", "Махачкала", "Томск", "Оренбург", "Кемерово",
    "Новокузнецк", "Рязань", "Астрахань", "Пенза", "Липецк",
    "Тула", "Киров", "Чебоксары", "Калининград", "Курск"
];

// Читает GET-параметр как boolean. Значение "true" (без регистра) -> true, всё остальное -> $default.
function getBoolParam(string $key, bool $default): bool
{
    if (!isset($_GET[$key])) 
        return $default;

    return strtolower((string)$_GET[$key]) === 'true';
}


//* --- Логика эндпоинта --- *//

$capitalize = getBoolParam('capitalize', true);
$transliter = getBoolParam('transliter', false);

$city = $cities[array_rand($cities)];

// 1. Регистр
if ($capitalize) 
    $city = mb_strtoupper(mb_substr($city, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($city, 1, null, 'UTF-8');
else 
    $city = mb_strtolower($city, 'UTF-8');

// 2. Транслитерация
if ($transliter)
    $city = Transliterate::convert($city);

// 3. Возвращаем результат
echo $city;