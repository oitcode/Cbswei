# Samarium - Business Management System

<div align="center">

[![Version: 0.9.6](https://img.shields.io/badge/Version-0.9.6-green.svg)](https://github.com/oitcode/samarium)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP->=8.2-777BB4?logo=php&logoColor=white)](https://php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-Framework-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL->=8.0-4479A1?logo=mysql&logoColor=white)](https://mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://docker.com/)

**A simple ERP system for small businesses - still growing and improving**

[Quick Start](#quick-start) • [Features](#current-features) • [Installation](#installation) • [Contributing](#contributing)

![screenshot](screenshots/dashboard-screenshot-1.png)

</div>

---

## About Samarium

Samarium is a work-in-progress business management system that aims to help small businesses handle basic operations like invoicing, inventory, and customer management in one place. While it's still in active development with many features being refined and added, it might be useful for simple business needs.

The project started as a learning exercise but has grown into something that could potentially help others who need basic business management tools without the complexity or cost of enterprise solutions.

## Current Features

**Please note**: Many features are still being developed and improved. What's available now:

### Basic ERP Functions
- Simple invoice generation
- Basic financial tracking (income/expenses)
- Customer database management
- Basic inventory tracking

### Point of Sale
- Simple checkout process
- Basic receipt generation
- Product management

### Website Management
- Simple CMS functionality
- Basic pages (About, Contact, etc.)
- Basic content management

### Additional Tools
- Task management (basic)
- Calendar system
- Notice board
- Simple analytics dashboard

### Customization Options
- Modular architecture (enable/disable features)
- Basic theme customization
- Configuration options

## Who Might Find This Useful

This might be helpful for:
- Small businesses looking for a simple, free alternative to expensive software
- Developers wanting to contribute to or learn from a Laravel-based business system
- Anyone needing basic business management tools while understanding this is still evolving

## Quick Start

### Using Make + Docker (Recommended)

Make is the easiest way to run Samarium. It handles secret generation, Docker
orchestration, migrations, and asset compilation in a single command.

**Prerequisites:** [Docker Desktop](https://www.docker.com/products/docker-desktop/),
`make` ([install on Windows](#windows))

```bash
git clone https://github.com/oitcode/samarium.git
cd samarium
make install
```

That's it. `make install` will:
1. Create `.env` from `.env.docker.example`
2. Generate random secrets for `APP_KEY`, `DB_PASSWORD`, `MYSQL_ROOT_PASSWORD`, and `ADMIN_PASSWORD`
3. Build the Docker image
4. Start all containers (app, MySQL, Redis) and wait until healthy
5. Run database migrations
6. Seed the database (admin user created from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env`)
7. Compile frontend assets

Visit **http://localhost:8000** — dashboard at **/dashboard**.

#### Daily commands

```bash
make up          # Start containers
make down        # Stop containers
make logs        # Stream container logs
make shell       # Open bash inside the app container

make migrate     # Run new migrations
make seed        # Re-seed the database
make fresh       # Drop all tables, migrate and seed from scratch
make assets      # Recompile frontend assets (development)
make assets-prod # Recompile frontend assets (minified)
make test        # Run the PHPUnit test suite

make ps          # Show container status
make clean CONFIRM=yes  # Remove all containers and volumes (destructive)
```

Run `make` with no arguments to see all available commands.

#### Customising before first run

Edit `.env` before running `make install` to change the admin credentials,
app URL, or any other setting:

```bash
# After cloning, generate the .env without starting containers:
make setup

# Edit .env — change ADMIN_EMAIL, ADMIN_PASSWORD, APP_URL, etc.
nano .env   # or open in your editor

# Then run the full install:
make install
```

#### Windows

Install `make` with one of these options, then run commands in **Git Bash** or **WSL**:

```bash
# Option A — Chocolatey (run PowerShell as Administrator)
choco install make

# Option B — Scoop
scoop install make

# Option C — WSL (Ubuntu already includes make)
sudo apt install make
```

> **WSL users:** run all commands from the WSL terminal, not PowerShell.
> The project path inside WSL will be `/mnt/d/samarium` (adjust drive letter as needed).

## Installation

<details>
<summary>Manual Installation (without Docker)</summary>

### Requirements
- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & npm

### Steps
```bash
git clone https://github.com/oitcode/samarium.git
cd samarium
cp .env.example .env

# Create database and update .env with your database credentials:
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

composer install
npm install
npm run dev

php artisan migrate
php artisan key:generate
php artisan storage:link
php artisan db:seed

php artisan serve
```

Access at `http://127.0.0.1:8000`

</details>

## Configuration

You can enable or disable modules in `config/app.php`:

```php
'modules' => [
    'dashboard' => true,
    'product' => true,
    'shop' => true,
    'calendar' => true,
    'analytics' => false, // Disable unused features
],
```

Theme colors can be customized in the same file:

```php
'app_menu_bg_color' => 'bg-dark',
'app_top_menu_bg_color' => 'bg-light',
```

## Current Limitations

Being honest about where things stand:
- Many features are still basic and need improvement
- Documentation could be much better
- Some modules are incomplete
- UI/UX needs work in many areas
- Testing coverage is limited
- Performance optimizations are needed

## Contributing

If you're interested in helping improve this project, contributions are very welcome:

- **Bug reports**: If something doesn't work as expected
- **Feature suggestions**: Ideas for improvements
- **Code contributions**: Help fix issues or add features
- **Documentation**: Help make things clearer for others
- **Testing**: Help identify problems

Feel free to open issues or pull requests. Even small improvements are appreciated.

## Technical Details

- **Backend**: Laravel (PHP), Livewire
- **Frontend**: Bootstrap, Livewire
- **Database**: MySQL
- **Containerization**: Docker support included

## License

MIT License - feel free to use, modify, and distribute.

## A Note

This project is still evolving. While it works for basic needs, please consider it as "early stage" software. If you try it and encounter issues or have suggestions, feedback would be genuinely helpful for making it better.

Thanks for taking a look at this project.
