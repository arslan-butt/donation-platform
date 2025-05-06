# Donation Platform

An internal platform for employee donations and CSR initiatives at ACME Corp.

## 🚀 Installation

1. **Clone the repository**

    ```bash
    git clone https://github.com/arslan-butt/donation-platform.git
    cd donation-platform
    ```

2. **Install dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Copy the .env file and generate the app key**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure your database**
   Edit the `.env` file and set your local DB connection or use SQLite without any configuration.

5. **Run migrations**

    ```bash
    php artisan migrate
    ```

6. **Build assets**
    ```bash
    npm run dev
    ```

## 🧪 Running Tests

```bash
php artisan test
```

## 🌱 Database Seeding

To seed the database with default permissions and demo users:

```bash
php artisan db:seed
```

This will create:

**Admin User**

- Email: admin@acme.com
- Password: password

**Regular User**

- Email: user@acme.com
- Password: password

**Run a Specific Seeder**

You can also run an individual seeder, e.g:

```bash
php artisan db:seed --class=CampaignSeeder
```

## 🛠 Scripts

- `npm run dev` - Start Vite dev server
- `npm run build` - Build production assets
- `npm run format` - Format frontend code with Prettier
- `npm run lint` - Lint and auto-fix code with ESLint

## 📦 Tech Stack

- Laravel (PHP)
- Vue 3 + Inertia.js
- Tailwind CSS
- Vite
- Pinia, Zod, VeeValidate
- ESLint + Prettier setup
