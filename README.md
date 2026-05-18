# Directory App

REST API for an internal company employee directory. Employees can belong to multiple departments (many-to-many relationship). Data management is available to authorized administrators only.

## Tech Stack

- **PHP 8.3** + **Laravel 13.8**
- **MySQL 8.0**
- **Nginx**
- **Laravel Sanctum** — token-based authentication
- **Docker** + **Docker Compose**

## Getting Started

### Requirements

- Docker
- Docker Compose

### Installation

1. Clone the repository:

```bash
git clone <repo-url>
cd directory-app
```

2. Create the environment file:

```bash
cp .env.example .env
```

3. Build and start the containers:

```bash
docker-compose up -d --build
```

4. Generate the application key:

```bash
docker-compose exec app php artisan key:generate
```

5. Run migrations and seed the database:

```bash
docker-compose exec app php artisan migrate --seed
```

The application will be available at: `http://localhost:8080`

## Database Structure

- `departments` — company departments
- `employees` — employees
- `department_employee` — many-to-many pivot table between employees and departments

## API Reference

### Public Endpoints (no authentication required)

| Method | URL | Description |
|---|---|---|
| GET | `/api/departments` | List all departments with employees |
| GET | `/api/departments/{id}` | Get a single department |
| GET | `/api/employees` | List all employees with departments |
| GET | `/api/employees/{id}` | Get a single employee |

### Authentication

| Method | URL | Description |
|---|---|---|
| POST | `/api/login` | Get access token |
| POST | `/api/logout` | Revoke access token |

Login request body:

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

Response:

```json
{
    "token": "1|abc123..."
}
```

Pass the token in the Authorization header for all protected requests:

```
Authorization: Bearer <token>
```

### Protected Endpoints (authentication required)

| Method | URL | Description |
|---|---|---|
| POST | `/api/departments` | Create a department |
| PUT | `/api/departments/{id}` | Update a department |
| DELETE | `/api/departments/{id}` | Delete a department |
| POST | `/api/employees` | Create an employee |
| PUT | `/api/employees/{id}` | Update an employee |
| DELETE | `/api/employees/{id}` | Delete an employee |

### Create Employee — Example Request Body

```json
{
    "first_name": "Maria",
    "last_name": "Petrova",
    "birthday": "1995-03-15",
    "gender": "female",
    "phone": "+79001112233",
    "email": "maria@example.com",
    "position": "Designer",
    "department_ids": [1, 2]
}
```

`department_ids` — array of department IDs to assign the employee to.

## Creating an Administrator

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

## Useful Commands

```bash
# Reset the database and reseed
docker-compose exec app php artisan migrate:fresh --seed

# List all API routes
docker-compose exec app php artisan route:list --path=api

# Stop containers
docker-compose down

# View application logs
docker-compose logs -f app
```
