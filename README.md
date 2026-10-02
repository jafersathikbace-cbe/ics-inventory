# ICS Inventory Management System

A role-based inventory and production-order management system built with **Laravel 12**, **PHP 8.2+**, **SQLite/MySQL**, **Blade**, and **Tailwind CSS**.

The application manages raw materials, composite materials, product bills of materials (BOMs), stock movements, production orders, reports, notifications, and user permissions from a single web interface.

## Core capabilities

- Role-based access control with Operator, Admin, and Super Admin roles
- Material and unit management
- Composite-material definitions and nested BOM expansion
- Product BOM management
- Stock checks, low-stock warnings, and transactional deductions
- Order lifecycle tracking
- Stock movement audit logs
- Daily PDF reports
- In-app notifications and optional Telegram notifications
- Scheduled report generation and report retention commands
- Super Admin ownership transfer workflow
- Profile and authentication management

## Architecture

```text
Browser
  │
  ▼
Laravel Routes + Permission Middleware
  │
  ├── Controllers ──► Form validation
  │        │
  │        ├── Services ──► inventory / ownership / reporting / Telegram
  │        │
  │        └── Eloquent Models ──► SQLite / MySQL
  │
  └── Blade + Tailwind UI
```

Business-critical inventory operations are kept in services so controllers remain focused on HTTP concerns.

## Local setup

Requirements:

- PHP 8.2+
- Composer
- Node.js 20+
- npm
- SQLite or MySQL

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
php artisan serve
```

Set `SEED_DEFAULT_PASSWORD` in `.env` before running the seeders. The seeders create demo users for the three application roles.

## Testing

```bash
php artisan test
```

The repository also includes static PHP syntax validation and a GitHub Actions workflow.

## Useful commands

Generate a daily report:

```bash
php artisan reports:generate-daily 2026-01-31
```

Purge reports older than six months:

```bash
php artisan reports:purge-old
```

## Configuration

Telegram notifications are optional. Configure the bot token and admin chat IDs in `.env` when required. The application can run without Telegram integration.

## Security notes

- Never commit `.env` or production credentials.
- Demo seed passwords are configured through an environment variable.
- Authorization is enforced with route-level permissions and roles.
- Stock deductions occur inside database transactions and use row locks during the final deduction step.

## Project structure

```text
app/
├── Console/Commands/      Scheduled report commands
├── Http/Controllers/      HTTP endpoints
├── Http/Requests/         Request validation
├── Models/                Eloquent models
├── Services/              Business logic
└── View/Components/       Blade components

database/
├── migrations/            Database schema
└── seeders/               Roles and demo data

resources/views/            Blade templates
routes/                     Web and authentication routes
tests/                      Feature and unit tests
```

## License

This project is released under the MIT License.
