## Установка

1. Клонируем репозиторий:

```bash
git clone 
cd hamroh-test
```

2. Устанавливаем зависимости:

```bash
composer install
```

3. Копируем файл конфигурации окружения:

```bash
cp .env.example .env
```

4. Настраиваем `.env` (подключение к базе данных):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hamroh
DB_USERNAME=root
DB_PASSWORD=
```

5. Генерируем ключ приложения:

```bash
php artisan key:generate
```

6. Запускаем миграции для создания таблиц:

```bash
php artisan migrate
```

7. Запускаем websocket:

```bash
php artisan reverb:start
```

8. Запускаем слушатель событий:

```bash
php artisan ride:listen
```
---

## Запуск проекта

Локальный сервер Laravel, Vuejs:

```bash
php artisan serve
```

```bash
npm install
```

```bash
npm run dev
```

По умолчанию доступно по адресу:

```
http://127.0.0.1:8000
```

---

## Swagger

Генерируем документацию:

```bash
php artisan l5-swagger:generate
```

3. Открываем Swagger UI:

```
http://127.0.0.1:8000/api/documentation
```
---
