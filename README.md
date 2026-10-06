# API генератор случайных значений

Небольшой REST API на чистом PHP без зависимостей и фреймворков. Генерирует случайные русские имена, названия российских городов, мобильные номера РФ и email-адреса.

Все эндпоинты возвращают **чистый текст** (`Content-Type: text/plain; charset=utf-8`) — без JSON, кавычек и обёрток. Авторизация не требуется.

---

## Содержание

- [Возможности](#возможности)
- [Структура проекта](#структура-проекта)
- [Требования](#требования)
- [Установка и запуск](#установка-и-запуск)
- [Эндпоинты](#эндпоинты)
  - [GET /API/V1/GET/random-name](#get-apiv1getrandom-name)
  - [GET /API/V1/GET/random-city](#get-apiv1getrandom-city)
  - [GET /API/V1/GET/random-phone](#get-apiv1getrandom-phone)
  - [GET /API/V1/GET/random-email](#get-apiv1getrandom-email)
- [Демо-страница](#демо-страница)
- [Класс Transliterate](#класс-transliterate)
- [Логика boolean-параметров](#логика-boolean-параметров)
- [Примеры использования](#примеры-использования)
- [Лицензия](#лицензия)

---

## Возможности

| Эндпоинт | Что возвращает | Параметры |
|---|---|---|
| `/API/V1/GET/random-name` | случайное русское имя | `capitalize`, `transliter` |
| `/API/V1/GET/random-city` | случайный российский город | `capitalize`, `transliter` |
| `/API/V1/GET/random-phone` | мобильный номер РФ | `raw` |
| `/API/V1/GET/random-email` | случайный email из базы | — |

- Без внешних зависимостей и Composer.
- Простая структура — каждый эндпоинт лежит в отдельной папке со своим `index.php`.
- Транслитерация по схеме, близкой к ГОСТ 7.79-2000 / BGN-PCGN, с сохранением регистра первой буквы.
- Готовая демо-страница `index.html` с кнопками «Выполнить» для каждого варианта запроса.

---

## Структура проекта

```
.
├── index.html                     # демо-страница с документацией и примерами
├── Transliterate.php              # класс транслитерации (namespace API\V1)
└── API/
    └── V1/
        └── GET/
            ├── random-name/
            │   └── index.php
            ├── random-city/
            │   └── index.php
            ├── random-phone/
            │   └── index.php
            └── random-email/
                ├── index.php
                └── emails.txt       # база email-адресов (по одному в строке)
```

> Путь до `Transliterate.php` в эндпоинтах указан как `__DIR__ . '/../../Transliterate.php'`. Если вы измените структуру каталогов — поправьте относительный путь или подключите класс через автозагрузчик.

---

## Требования

- **PHP 7.4+** (используются типизированные аргументы, `??`, `mb_*`).
- Включённое расширение **mbstring**.
- Любой веб-сервер (Apache, Nginx + PHP-FPM) или встроенный сервер PHP.

---

## Установка и запуск

### Вариант 1. Встроенный сервер PHP (для разработки)

```bash
git clone https://github.com/<username>/<repo>.git
cd <repo>

php -S localhost:8000
```

Откройте в браузере:

- демо-страница — http://localhost:8000/index.html
- пример API — http://localhost:8000/API/V1/GET/random-name

### Вариант 2. Apache / Nginx

Разместите файлы в корне виртуального хоста. Убедитесь, что:

- каталог `API/` доступен для чтения;
- файл `emails.txt` доступен веб-серверу (права на чтение).

Пример конфигурации Nginx:

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/random-api;
    index index.html index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

## Эндпоинты

### GET /API/V1/GET/random-name

Возвращает случайное русское имя.

| Параметр | Тип | По умолчанию | Описание |
|---|---|---|---|
| `capitalize` | boolean | `true` | С большой буквы, если `true`; с маленькой — если `false` |
| `transliter` | boolean | `false` | Транслитерация латиницей, если `true` |

Примеры:

```
/API/V1/GET/random-name                                   → Мария
/API/V1/GET/random-name?capitalize=false                  → мария
/API/V1/GET/random-name?transliter=true                   → Mariya
/API/V1/GET/random-name?capitalize=false&transliter=true  → mariya
```

---

### GET /API/V1/GET/random-city

Возвращает случайный российский город.

| Параметр | Тип | По умолчанию | Описание |
|---|---|---|---|
| `capitalize` | boolean | `true` | С большой буквы, если `true`; с маленькой — если `false` |
| `transliter` | boolean | `false` | Транслитерация латиницей, если `true` |

Примеры:

```
/API/V1/GET/random-city                                   → Москва
/API/V1/GET/random-city?capitalize=false                  → москва
/API/V1/GET/random-city?transliter=true                   → Moskva
/API/V1/GET/random-city?capitalize=false&transliter=true  → moskva
```

---

### GET /API/V1/GET/random-phone

Возвращает случайный мобильный номер РФ.

| Параметр | Тип | По умолчанию | Описание |
|---|---|---|---|
| `raw` | boolean | `false` | Без форматирования, если `true` |

Примеры:

```
/API/V1/GET/random-phone            → +7 (947) 231-08-56
/API/V1/GET/random-phone?raw=true   → +79472310856
```

---

### GET /API/V1/GET/random-email

Возвращает случайный email из файла `emails.txt`.

Параметров нет.

```
/API/V1/GET/random-email → armatura2011@mail.ru
```

Чтобы пополнить базу — просто добавьте новые адреса в `emails.txt` (по одному в строке).

---

## Демо-страница

Файл `index.html` — это готовая страница с документацией и кнопками «Выполнить» для каждого варианта запроса.
https://myqu.ru/API/V1

---

## Класс Transliterate

`Transliterate.php` (namespace `API\V1`) — статический класс без состояния.

```php
use API\V1\Transliterate;

echo Transliterate::convert('Александр'); // Aleksandr
echo Transliterate::convert('москва');    // moskva
```

Особенности:

- Карта букв близка к ГОСТ 7.79-2000 / BGN-PCGN.
- Сохраняет регистр первой буквы: `Москва` → `Moskva`, `москва` → `moskva`.
- `ё` → `e`, `ъ` и `ь` → пустая строка.

---

## Логика boolean-параметров

Во всех эндпоинтах используется одна и та же функция:

```php
function getBoolParam(string $key, bool $default): bool
{
    if (!isset($_GET[$key]))
        return $default;

    return strtolower((string)$_GET[$key]) === 'true';
}
```

Правила:

- Параметр отсутствует → берётся значение по умолчанию.
- Значение `true` (в любом регистре: `true`, `True`, `TRUE`) → `true`.
- Любое другое значение (`false`, `1`, `yes`, пустая строка) → `false`.

---

## Примеры использования

### cURL

```bash
curl https://example.com/API/V1/GET/random-name
curl "https://example.com/API/V1/GET/random-city?transliter=true"
curl "https://example.com/API/V1/GET/random-phone?raw=true"
curl https://example.com/API/V1/GET/random-email
```

### PHP

```php
$name  = file_get_contents('https://example.com/API/V1/GET/random-name');
$phone = file_get_contents('https://example.com/API/V1/GET/random-phone?raw=true');
```

### JavaScript

```js
const city = await fetch('/API/V1/GET/random-city?transliter=true').then(r => r.text());
console.log(city);
```

### Python

```python
import requests

name = requests.get('https://example.com/API/V1/GET/random-name').text
print(name)
```

---

## Лицензия

MIT — используйте свободно, в том числе в коммерческих проектах.

