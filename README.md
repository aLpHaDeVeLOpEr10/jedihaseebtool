# JEDISEBITOOL — Free Online Tools Platform

A production-ready **Laravel 11** multi-tool platform where admins can dynamically create, manage, and deploy online tools. Built with Blade, Tailwind CSS 3, Alpine.js, and MySQL.

---

## ✨ Features

- **95+ pre-built tools** across 33 categories
- **Dynamic tool engine** — calculators, converters, generators, text tools, productivity tools
- **Admin dashboard** — create, edit, enable/disable tools without touching code
- **Blade generator** — admin can auto-generate custom Blade templates per tool
- **SEO-ready** — custom meta titles/descriptions, OG tags, structured data (FAQPage, WebApplication)
- **Category system** — 33 categories with icons, colors, SEO fields
- **Currency converter** — 28 currencies, admin-managed rates
- **Settings system** — manage site name, hero text, analytics, currency rates from admin
- **Contact form** — admin inbox with read/unread tracking
- **Alpine.js powered** — instant form interactions, FAQ accordions, search toggles
- **Rate limiting** — tool processing endpoint throttled at 30/min
- **Soft deletes** — tools can be trashed and restored

---

## 🔧 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js |
| Build Tool | Vite |
| Database | MySQL 8+ |
| Auth | Laravel session auth (built-in) |

---

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ + npm
- MySQL 8+

### Step 1 — Clone and install

```bash
git clone https://github.com/yourorg/jedisebitool.git
cd jedisebitool

composer install
npm install
```

### Step 2 — Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:

```env
APP_NAME=JEDISEBITOOL
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jedisebitool
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### Step 3 — Database setup

```bash
# Create the database first (in MySQL)
mysql -u root -p -e "CREATE DATABASE jedisebitool CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations and seed data
php artisan migrate --seed
```

This seeds:
- Admin user: `admin@jedisebitool.com` / `password123`
- 14 default settings (site name, currency rates, SEO, etc.)
- 33 categories
- 15 fully-wired working tools + 80+ stub tools

### Step 4 — Build assets

```bash
# Development
npm run dev

# Production build
npm run build
```

### Step 5 — Start the server

```bash
php artisan serve
```

Visit `http://localhost:8000`

---

## 👤 Admin Access

| URL | Credentials |
|-----|------------|
| `/login` | admin@jedisebitool.com |
| `/admin` | password: `password123` |

---

## 🛠 Working Tools (Fully Implemented)

| Tool | URL | Engine |
|------|-----|--------|
| Percentage Calculator | `/tools/percentage-calculator` | `CalculatorEngine::percentage()` |
| BMI Calculator | `/tools/bmi-calculator` | `CalculatorEngine::bmi()` |
| Loan Calculator | `/tools/loan-calculator` | `CalculatorEngine::loan()` |
| Tip Calculator | `/tools/tip-calculator` | `CalculatorEngine::tip()` |
| Date Difference | `/tools/date-difference-calculator` | `CalculatorEngine::dateDiff()` |
| Random Number | `/tools/random-number-generator` | `CalculatorEngine::randomNumber()` |
| Unit Converter | `/tools/unit-converter` | `ConverterEngine::unitConverter()` |
| Currency Converter | `/tools/currency-converter` | `ConverterEngine::currency()` |
| Password Generator | `/tools/password-generator` | `GeneratorEngine::password()` |
| QR Code Generator | `/tools/qr-code-generator` | `GeneratorEngine::qrCode()` |
| Color Palette Generator | `/tools/color-palette-generator` | `GeneratorEngine::colorPalette()` |
| JSON Formatter | `/tools/json-formatter` | `TextToolEngine::jsonFormatter()` |
| Text Summarizer | `/tools/text-summarizer` | `TextToolEngine::textSummarizer()` |
| To-Do List | `/tools/todo-list` | Client-side Alpine.js + localStorage |
| Notes App | `/tools/notes-app` | Client-side Alpine.js + localStorage |

---

## ➕ Adding a New Tool (Admin)

### Method 1 — Admin Dashboard (no code)

1. Go to `/admin/tools/create`
2. Fill in: name, slug, category, type, description, icon, color
3. Add input fields using the visual builder
4. Add FAQs
5. Click **Create Tool** → tool is live instantly

### Method 2 — With Custom Engine

1. Create a new method in the appropriate engine class (e.g. `app/Services/CalculatorEngine.php`)
2. In the tool's admin edit page, set **Engine Class** and **Engine Method**
3. The `ToolEngine` dispatcher will route requests to your method automatically

### Method 3 — Custom Blade

1. Create/edit a tool in admin
2. Go to the **Blade Template** tab → click **Generate Template**
3. Edit the blade file directly in the admin editor
4. The tool will use your custom view instead of the generic renderer

---

## 📁 Project Structure

```
jedisebitool/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Dashboard, Tools, Categories, Settings, Contacts
│   │   ├── Auth/           # Login
│   │   ├── HomeController  # Public pages
│   │   ├── ToolController  # Tool listing, show, process, search
│   │   └── CategoryController
│   ├── Models/             # User, Tool, Category, Setting, ToolInput, ToolFaq, Contact
│   ├── Services/           # ToolEngine, CalculatorEngine, ConverterEngine, GeneratorEngine, TextToolEngine, BladeGeneratorService
│   └── Http/Middleware/    # AdminMiddleware
├── database/
│   ├── migrations/         # All schema files
│   └── seeders/            # DatabaseSeeder, UserSeeder, SettingsSeeder, CategorySeeder, ToolSeeder
├── resources/
│   ├── css/app.css         # Tailwind + component classes
│   ├── js/app.js           # Alpine.js + JST utilities
│   └── views/
│       ├── layouts/        # public.blade.php, admin.blade.php
│       ├── partials/       # navbar, footer
│       ├── components/     # tool-card
│       ├── public/         # Home, tools, categories, search, static pages
│       ├── admin/          # Dashboard, tools CRUD, categories, settings, contacts
│       ├── auth/           # Login
│       ├── errors/         # 404, 500
│       └── tools/generated/ # Auto-generated blade files (gitignored)
└── routes/web.php          # All routes
```

---

## ⚙️ Settings Reference

Configurable from `/admin/settings`:

| Key | Description |
|-----|------------|
| `site_name` | Site name shown in header & titles |
| `site_tagline` | One-line tagline |
| `site_description` | Footer/About description |
| `contact_email` | Contact form recipient |
| `footer_text` | Footer copyright text |
| `home_hero_title` | Homepage hero headline |
| `home_hero_subtitle` | Homepage hero subtitle |
| `enable_search` | Show/hide search bar |
| `maintenance_mode` | Put site in maintenance mode |
| `tools_per_page` | Pagination (default: 24) |
| `google_analytics` | GA4 Measurement ID |
| `seo_title_suffix` | Appended to page titles |
| `seo_default_description` | Default meta description |
| `currency_rates` | JSON: exchange rates vs USD |

---

## 🔒 Security

- CSRF protection on all forms and AJAX requests
- Rate limiting on tool processing (30 req/min)
- Admin middleware checks auth + role
- Tool results never stored; processed in-request
- Client-side tools (todo, notes) use localStorage — data never touches server

---

## 🚢 Deployment (Production)

```bash
# 1. Set production env
APP_ENV=production
APP_DEBUG=false

# 2. Build assets
npm run build

# 3. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Set storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Run migrations
php artisan migrate --force
```

---

## 📝 License

MIT — Free to use and modify.
