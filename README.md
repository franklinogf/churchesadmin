# Churches Administration

[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F5129b258-7cf5-4173-8aef-861ede4b0c1a%3Flabel%3D1&style=flat)](https://forge.laravel.com/servers/910633/sites/2692376)
[![tests](https://github.com/franklinogf/churchesadmin/actions/workflows/tests.yml/badge.svg)](https://github.com/franklinogf/churchesadmin/actions/workflows/tests.yml)
[![linter](https://github.com/franklinogf/churchesadmin/actions/workflows/lint.yml/badge.svg)](https://github.com/franklinogf/churchesadmin/actions/workflows/lint.yml)

ChurchesAdmin is a comprehensive church management system. This multi-tenant application helps churches manage their finances, members, missionaries, offerings, and other administrative tasks.

## Features

- **Multi-tenancy**: Each church gets their own isolated environment using subdomain-based tenancy
- **Member Management**: Track church members with contact details and personal information
- **Financial Management**:
  - Church wallets for managing funds
  - Check printing system with customizable layouts
  - Expense tracking
  - Offering management
- **Missionary Management**: Track missionary information, contact details, and offerings
- **Multilingual Support**: Currently supports English and Spanish
- **Role-Based Permissions**: Secure access control with defined permissions
- **Responsive UI**: Modern reactive UI using React 19 and Inertia.js

## Tech Stack

- **Backend**:
  - Laravel 12
  - PHP 8.2+
  - Stancl Tenancy for multi-tenant functionality
  - Spatie packages (Media Library, Permissions, Tags, Translatable)
  - Bavix Wallet for financial management
  - DomPDF for check printing
  - Pest for testing

- **Frontend**:
  - React 19
  - Inertia.js for server-driven SPA
  - TypeScript
  - TailwindCSS
  - Shadcn ui components
  - Lucide icons

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- Database (MySQL, PostgreSQL, or SQLite)

## Installation

1. Clone the repository:

```bash
git clone [repository-url] churchesadmin
cd churchesadmin
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install JavaScript dependencies:

```bash
npm install
```

4. Copy environment file and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=churchesadmin
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations:

```bash
php artisan migrate
```

7. Seed the database:

```bash
php artisan db:seed
```

8. Create storage links:

```bash
php artisan storage:link
```

9. Build assets:

```bash
npm run build
```

## Development

Run the development server:

```bash
# Run server, queue worker, reverb, and Vite in parallel
composer run dev
```

## Testing

Run all tests:

```bash
composer test
```

Run specific test suites:

```bash
composer test:lint     # linting tests (pint)
composer test:refactor     # formating tests (rector)
composer test:types     # types tests (phpstan and pest type coverage)
composer test:typos     # typos tests (peck)
composer test:all  # Unit tests and Feature tests
```


## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.
