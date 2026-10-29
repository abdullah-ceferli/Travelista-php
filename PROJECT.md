# Travelista PHP project

The application now lives in the repository root and runs on Laravel 12, PHP 8.3+, and MySQL.
The existing interface is retained.

See [README.md](README.md) for setup, database, email, Google sign-in,
admin access, and test instructions.

From the repository root:

```sh
composer install
php artisan migrate
php artisan storage:link
php artisan serve
```

Use the existing `.env` for this installation. The MySQL database is
`travelista`; migrations define all application tables. Never use
`migrate:fresh` against a database containing data you want to retain.
