# QuickBite

QuickBite is a Laravel API for food ordering. It includes restaurant and menu
management, carts, orders, delivery workflows, payments, administrator
endpoints, and AI-powered food discovery.

## Requirements

- PHP 8.3 or later with the extensions required by Laravel
- Composer
- Node.js and npm
- PostgreSQL

## Installation

1. Clone the repository and enter the project directory:

   ```bash
   git clone <repository-url>
   cd Quickbite
   ```

2. Install PHP dependencies and create your local environment file:

   ```bash
   composer install
   ```

   If `.env` was not created automatically, copy the example:

   ```bash
   cp .env.example .env
   ```

   On Windows PowerShell, use:

   ```powershell
   Copy-Item .env.example .env
   ```

3. Create a PostgreSQL database, then set its connection details in `.env`.
   The example file uses PostgreSQL (`DB_CONNECTION=pgsql`); set `DB_HOST`,
   `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for your local
   database.

4. Generate the application key and run the database migrations:

   ```bash
   php artisan key:generate
   php artisan migrate
   ```

   To load the included sample data as well, run:

   ```bash
   php artisan db:seed
   ```

5. Install the frontend dependencies and build the assets:

   ```bash
   npm install
   npm run build
   ```

6. Start the full local development stack:

   ```bash
   composer run dev
   ```

   This starts the Laravel web server, queue worker, Vite, and Reverb. If you
   only need the API and frontend, run `php artisan serve` and `npm run dev` in
   separate terminals instead.

## Environment configuration

The `.env.example` file documents the available settings. Configure these as
needed for your environment:

- **Database:** PostgreSQL connection values must point to a reachable database
  before running migrations.
- **AI food discovery:** Set `OPENAI_API_KEY` to use the default OpenAI provider.
  The application also supports other providers; see `config/ai.php` for the
  configured provider names and their environment variables.
- **Payment webhooks:** Set `PAYMENT_WEBHOOK_SECRET` to the secret used to
  validate incoming payment webhook signatures.
- **Realtime broadcasting:** Reverb settings are included in `.env.example`.
  Configure its app credentials when using broadcasting. The full development
  stack starts Reverb automatically; to run it separately, use
  `php artisan reverb:start`.

Keep credentials in your local `.env` file and never commit them.

## API

API routes are defined in `routes/api.php` and are available under the `/api`
prefix. Most routes require a Laravel Sanctum bearer token. For example, login
is available at `POST /api/login`; authenticated clients can use
`GET /api/me`. The AI food discovery endpoint is `POST /api/ai/food-discovery`.

## Tests

Run the test suite with:

```bash
php artisan test
```
