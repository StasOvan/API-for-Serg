<?php

header('Content-Type: text/plain; charset=utf-8');

/*
text/plain
/API/V1/GET/random-email	armatura2011@mail.ru
*/


//* --- Логика эндпоинта --- *//

$file = __DIR__ . '/emails.txt';

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$email = $lines[array_rand($lines)];

// Возвращаем результат
echo $email;