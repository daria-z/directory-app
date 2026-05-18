# Directory App

REST API для внутреннего справочника сотрудников компании. Сотрудники могут принадлежать к нескольким отделам (many-to-many). Управление данными доступно только авторизованному администратору.

## Стек

- **PHP 8.4** + **Laravel 11**
- **MySQL 8.0**
- **Nginx**
- **Laravel Sanctum** — авторизация через токены
- **Docker** + **Docker Compose**

## Запуск проекта

### Требования

- Docker
- Docker Compose

### Установка

1. Клонируй репозиторий:

```bash
git clone <repo-url>
cd directory-app
```

2. Создай файл окружения:

```bash
cp .env.example .env
```

3. Собери и запусти контейнеры:

```bash
docker-compose up -d --build
```

4. Сгенерируй ключ приложения:

```bash
docker-compose exec app php artisan key:generate
```

5. Запусти миграции и заполни базу тестовыми данными:

```bash
docker-compose exec app php artisan migrate --seed
```

Приложение доступно по адресу: `http://localhost:8080`

## Структура базы данных

- `departments` — отделы компании
- `employees` — сотрудники
- `department_employee` — связь many-to-many между сотрудниками и отделами

## API

### Публичные эндпоинты (без авторизации)

| Метод | URL | Описание |
|---|---|---|
| GET | `/api/departments` | Список всех отделов с сотрудниками |
| GET | `/api/departments/{id}` | Один отдел |
| GET | `/api/employees` | Список всех сотрудников с отделами |
| GET | `/api/employees/{id}` | Один сотрудник |

### Авторизация

| Метод | URL | Описание |
|---|---|---|
| POST | `/api/login` | Получить токен |
| POST | `/api/logout` | Удалить токен |

Пример запроса на логин:

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

Ответ:

```json
{
    "token": "1|abc123..."
}
```

Токен передаётся в заголовке всех защищённых запросов:

```
Authorization: Bearer <token>
```

### Защищённые эндпоинты (требуют токен)

| Метод | URL | Описание |
|---|---|---|
| POST | `/api/departments` | Создать отдел |
| PUT | `/api/departments/{id}` | Обновить отдел |
| DELETE | `/api/departments/{id}` | Удалить отдел |
| POST | `/api/employees` | Создать сотрудника |
| PUT | `/api/employees/{id}` | Обновить сотрудника |
| DELETE | `/api/employees/{id}` | Удалить сотрудника |

### Пример создания сотрудника

```json
{
    "first_name": "Мария",
    "last_name": "Петрова",
    "birthday": "1995-03-15",
    "gender": "female",
    "phone": "+79001112233",
    "email": "maria@example.com",
    "position": "Designer",
    "department_ids": [1, 2]
}
```

`department_ids` — массив id отделов к которым привязывается сотрудник.

## Создание администратора

```bash
docker-compose exec app php artisan tinker
```

```php
use App\Models\User;
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
]);
```

## Полезные команды

```bash
# Пересоздать базу и заполнить тестовыми данными
docker-compose exec app php artisan migrate:fresh --seed

# Посмотреть все роуты
docker-compose exec app php artisan route:list --path=api

# Остановить контейнеры
docker-compose down

# Посмотреть логи
docker-compose logs -f app
```
