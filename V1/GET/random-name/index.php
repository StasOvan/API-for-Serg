<?php

header('Content-Type: text/plain; charset=utf-8');

require __DIR__ . '/../../Transliterate.php';

use API\V1\Transliterate;

/*
text/plain
по умолчанию: capitalize=true
по умолчанию: transliter=false

/API/V1/GET/random-name	Мария
/API/V1/GET/random-name?capitalize=false	мария
/API/V1/GET/random-name?transliter=true	Mariya
/API/V1/GET/random-name?capitalize=false&transliter=true	mariya
*/

$names = [
    "Александр", "Сергей", "Владимир", "Андрей", "Алексей", "Дмитрий", "Елена", "Татьяна",
    "Евгений", "Николай", "Наталья", "Ольга", "Юрий", "Игорь", "Михаил", "Ирина",
    "Виктор", "Светлана", "Олег", "Валерий", "Анатолий", "Людмила", "Галина", "Павел",
    "Иван", "Максим", "Марина", "Анна", "Вячеслав", "Юлия", "Валентина", "Денис",
    "Роман", "Екатерина", "Константин", "Надежда", "Виталий", "Василий", "Мария", "Любовь",
    "Геннадий", "Антон", "Вадим", "Нина", "Оксана", "Лариса", "Илья", "Анастасия",
    "Борис", "Руслан", "Станислав", "Владислав", "Вера", "Петр", "Леонид", "Артем",
    "Наталия", "Евгения", "Эдуард", "Тамара", "Виктория", "Кирилл", "Александра", "Лидия",
    "Григорий", "Георгий", "Артур", "Валентин", "Алла", "Никита", "Инна", "Раиса", "Лилия"
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

$name = $names[array_rand($names)];

// 1. Регистр
if ($capitalize) 
    $name = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, null, 'UTF-8');
else 
    $name = mb_strtolower($name, 'UTF-8');

// 2. Транслитерация
if ($transliter)
    $name = Transliterate::convert($name);

// 3. Возвращаем результат
echo $name;