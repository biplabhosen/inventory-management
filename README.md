# Inventory Management System (Laravel 12)

Production-ready Inventory Management + double-entry accounting system built with Laravel 12, PHP 8.2+, MySQL, Blade, and Bootstrap 5.

## Project Overview

This system provides:

- Product management with opening stock capitalization.
- Sale recording with discount, VAT, partial payment, and due tracking.
- Automated double-entry journal posting via `AccountingService`.
- Financial-safe stock and sale posting through DB transactions in `SaleService`.
- Dashboard KPIs, journal ledger, and financial report by date range.
- Railway-ready deployment config (`railway.json`, `.env.example`).

## Accounting Explanation

### 1) Opening Stock Posting

When a product is created, opening stock value is:

`opening_inventory_value = purchase_price x opening_stock`

Journal:

- Dr Inventory
- Cr Capital

### 2) Sale Posting

For each sale:

- `subtotal = quantity x unit_price`
- `net_sale = subtotal - discount`
- `vat_amount = net_sale x vat_percent / 100`
- `total_receivable = net_sale + vat_amount`
- `due_amount = total_receivable - paid_amount`
- `cogs = quantity x purchase_price`

Journal entries:

- Revenue entry: Dr Accounts Receivable (total_receivable), Cr Sales Revenue (net_sale), Cr VAT Payable (vat_amount)
- COGS entry: Dr Cost of Goods Sold (cogs), Cr Inventory (cogs)
- Payment entry: Dr Cash (paid_amount), Cr Accounts Receivable (paid_amount)

All entries are generated automatically by `app/Services/AccountingService.php`.

## Journal Entry Explanation (Given Scenario)

Seeded scenario:

- Product: purchase 100 TK, sell 200 TK, opening stock 50
- Sale: qty 10, discount 50 TK, VAT 5%, paid 1000 TK

Computed values:

- Opening inventory value: `100 x 50 = 5000`
- Subtotal: `10 x 200 = 2000`
- Net sale: `2000 - 50 = 1950`
- VAT: `1950 x 5% = 97.50`
- Total receivable: `1950 + 97.50 = 2047.50`
- Due: `2047.50 - 1000 = 1047.50`
- COGS: `10 x 100 = 1000`

## Tech and Architecture

- Laravel 12 MVC structure
- Service layer with `SaleService` for stock validation, calculation, and atomic sale posting
- Service layer with `AccountingService` for journal posting rules
- MySQL-only runtime defaults
- DB transaction usage in sale flow

## Setup Instructions

1. Install dependencies:

```bash
composer install
```

2. Create environment file:

```bash
cp .env.example .env
```

3. Set MySQL credentials in `.env`.

4. Generate app key:

```bash
php artisan key:generate
```

5. Run migrations and seed data:

```bash
php artisan migrate --seed
```

6. Start locally:

```bash
php artisan serve
```

## Financial Report Endpoint

`GET /report?from=YYYY-MM-DD&to=YYYY-MM-DD`

Shows:

- Total Sales (`SUM(sales.total_amount)`)
- Total Expense (`SUM(journal_entries.debit where account = COGS)`)
- Total Profit (`Sales - Expense`)
- Total Due (`SUM(sales.due_amount)`)

## Railway Deployment Steps

1. Push this project to GitHub.
2. Create a new Railway project from the repository.
3. Add a Railway MySQL service.
4. Ensure app service variables are set (see next section).
5. Deploy with `railway.json` settings (automatic migrations on startup, Laravel cache warmup, and Railway port binding).

No SQLite is used in runtime defaults.

## Environment Variable Setup

Important variables:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=mysql`
- `DB_HOST=${MYSQLHOST}`
- `DB_PORT=${MYSQLPORT}`
- `DB_DATABASE=${MYSQLDATABASE}`
- `DB_USERNAME=${MYSQLUSER}`
- `DB_PASSWORD=${MYSQLPASSWORD}`
- `APP_KEY` (must be generated and set)
- `DEFAULT_VAT_PERCENT=5` (optional override)

Use the provided `.env.example` as the baseline.

## Demo Credentials

Seeded admin user:

- Email: `admin@inventory.test`
- Password: `admin12345`

Seeded data:

- Demo product with opening stock journal
- Sample sale with full accounting journal flow
