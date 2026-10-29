# Travelista PHP

The existing Travelista interface runs on PHP 8.3+ and Laravel 12 with MySQL.
Its layout, styles, and static travel content are preserved. Blade templates,
session authentication, validated forms, Eloquent models, and migrations replace
the former Django bindings. Python is not required to run the application.

## Local setup

Run commands from the project root (the directory containing `artisan`):

```sh
composer install
cp .env.example .env
php artisan key:generate
```

On PowerShell, use `Copy-Item .env.example .env` instead of `cp`.
For an existing installation, preserve its `.env` and `APP_KEY`.

Create the database once:

```sql
CREATE DATABASE travelista CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Configure `.env` for your MySQL connection:

```dotenv
APP_NAME=Travelista
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=travelista
DB_USERNAME=root
DB_PASSWORD=
SESSION_ENCRYPT=true
```

Then run:

```sh
php artisan config:clear
php artisan migrate
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000. The web server document root must be `public/`.
The existing CSS and JavaScript are served directly; a Node/Vite build is not
needed for these pages. Set `APP_URL` to the actual site URL so uploaded image URLs work.

## Existing screen behavior

- Registration emails a six-digit code, expires it after ten minutes, and limits
  incorrect attempts. The pending password and code are hashed, and sessions are encrypted.
- Login uses Laravel's session guard; logout is a CSRF-protected POST.
- Profile setup stores the existing fields and validated images on the public disk.
- Blog publication saves posts, tags, and optional images. Listings support search,
  tag filtering, and pagination; detail pages show views and escaped comments.
- Contact submissions are saved privately. Set a contact message's `is_published`
  flag to true to include it in the existing testimonial areas.
- Existing newsletter forms store unique email subscriptions.
- Connect supports private conversations and saved messages, with participant
  checks on both reads and writes. It polls for the latest 200 messages.
- Static travel pages and original placeholder links remain as supplied.
  Live flight/hotel inventory, bookings, and external social links require
  separate provider integrations; no booking service is fabricated.

## Email

The local `.env` now contains the SMTP settings transferred from Django. No test
email was sent during configuration. The safe example file still defaults to
`MAIL_MAILER=log`, which writes verification codes to `storage/logs/laravel.log`.
To configure another SMTP provider,
configure `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`,
`MAIL_PASSWORD`, and `MAIL_FROM_ADDRESS` for your provider. Run
`php artisan config:clear` after changing configuration.

The transferred Gmail configuration uses port 587, required STARTTLS, and
`MAIL_TIMEOUT=10`. SMTP delivery still depends on the source credential being valid.

## Google sign-in

The existing buttons use Laravel Socialite. Configure `GOOGLE_CLIENT_ID`,
`GOOGLE_CLIENT_SECRET`, and `GOOGLE_REDIRECT_URI` in `.env`.
Register that exact callback URL, for example
`http://127.0.0.1:8000/auth/google/callback`, in your Google OAuth client.
Without credentials the existing auth page explains that email sign-in is available.

The local Google client ID and secret were transferred from Django. The callback
was translated to `${APP_URL}/auth/google/callback`; that Laravel callback must also
be authorized in the Google OAuth client configuration. The Django callback URL
does not apply to this application.

OAuth state is checked against the initiating session. Only verified Google
email addresses are accepted. Accounts with an existing password are not
automatically linked solely by matching an email address.

## Admin access

The existing Filament panel is at `/admin`. Ordinary public accounts cannot
access it. After creating an account through registration or
`php artisan make:filament-user`, explicitly grant access:

```sh
php artisan travelista:admin your-email@example.com
```

## Validation

```sh
php artisan test
php vendor/laravel/pint/builds/pint --test
php artisan view:cache
composer audit
```

Feature tests migrate an isolated in-memory SQLite database; they do not reset
your MySQL database. Tests cover rendering with empty and populated data,
registration and verification, authentication, uploads, posts, search,
pagination, comments, testimonials, subscriptions, Google sign-in, CSRF, and
chat isolation.

## Django comparison and deployment settings

See [the migration audit](docs/django-migration-audit.md) for the exact feature,
asset, and database gaps. Existing Django database records and uploaded media
have not been imported. The public pages being present does not mean the entire
Django application has been ported.

`GEMINI_API_KEY` and all `WHATSAPP_*` settings were retained in the private `.env`
and exposed through `config/services.php`. The moderation and WhatsApp feature
code is still absent; credentials alone do not enable those integrations.

The Django security settings are mapped through `config/travelista.php`:
`ALLOWED_HOSTS`, exact `CSRF_TRUSTED_ORIGINS`, HTTPS redirection, and HSTS settings.
`SESSION_SECURE_COOKIE` secures both Laravel session and XSRF cookies. Forwarded
HTTPS headers require both `USE_X_FORWARDED_PROTO=true` and an explicit
`TRUSTED_PROXIES` allowlist. Laravel's existing `APP_KEY` and MySQL connection
are preserved; Django signing keys cannot replace `APP_KEY`.

In production, configure HTTPS, `APP_ENV=production`, `APP_DEBUG=false`,
`SESSION_SECURE_COOKIE=true`, a real mail transport, database credentials,
writable `storage`/`bootstrap/cache` directories, and the public storage link.
