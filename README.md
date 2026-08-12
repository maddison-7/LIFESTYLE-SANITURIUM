# Lifestyle Sanitarium Clinic

**Afya Bora, Maisha Bora.**

A clinic management platform for Lifestyle Sanitarium Clinic — a public-facing marketing/booking site, a patient self-service portal, and a role-based admin dashboard for running the clinic day to day (appointments, patients, payments, services, branches, healthcare team, health education content, pharmacy/inventory, reports, analytics and notifications). The public site, patient portal and auth pages support light/dark mode and English/Kiswahili.

Built as a monolithic Laravel application: Laravel 13, PHP 8.4, MySQL, Blade, Tailwind CSS v4, Alpine.js. No SPA framework, no separate API layer.

## Tech Stack

- **Backend**: Laravel 13 (PHP 8.4+), Eloquent ORM, Form Requests for validation, Policies/Gates for authorization
- **Frontend**: Blade components, Tailwind CSS v4 (via Vite), Alpine.js for interactivity (mobile nav, modals, dropdowns)
- **Database**: MySQL
- **Testing**: PHPUnit (Feature tests against a real MySQL test database)

## Local Setup

Prerequisites: PHP 8.4+ with `pdo_mysql`, Composer, Node 18+, a MySQL server.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Create the database and point `.env` at it (defaults already match a local XAMPP/MySQL setup):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lifestyle_sanitarium
DB_USERNAME=root
DB_PASSWORD=
```

Then:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build      # or `npm run dev` while actively developing
php artisan serve
```

Visit `http://127.0.0.1:8000`. Admin dashboard: `http://127.0.0.1:8000/admin/login`.

### Seeded admin login (development only)

The seeder creates one Super Admin account from `.env`:

```
ADMIN_EMAIL=admin@lifestylesanitarium.test
ADMIN_PASSWORD=LifestyleAdmin@2026
```

**Change or rotate this password before any real deployment.** It exists so the dashboard is reachable out of the box in local development.

### What gets seeded, and what deliberately doesn't

`WebsiteSettingSeeder`, `BranchSeeder`, `ServiceSeeder`, and `AdminUserSeeder` populate only the real information provided for this clinic (two branches, seven core service categories, phone/WhatsApp numbers, the medical disclaimer) plus the one admin account above. Healthcare team members, health articles, patients, and medicines are **not** seeded with placeholder data — those tables start empty and are populated through the admin dashboard, since inventing doctors, articles, or patient records would misrepresent the real clinic.

## Running Tests

The test suite uses **MySQL**, not SQLite — this environment's PHP build doesn't have `pdo_sqlite`. If yours does and you'd rather use an in-memory SQLite DB, edit `phpunit.xml`'s `<php>` block back to `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:`.

Create a separate test database once:

```sql
CREATE DATABASE lifestyle_sanitarium_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

(`phpunit.xml` already points at `lifestyle_sanitarium_test` with `root`/no password — adjust there if your local MySQL credentials differ.)

```bash
php artisan test
```

45 feature tests cover public page rendering, the appointment booking flow (validation, patient dedup-by-phone, notification fan-out), authentication (login/lockout/inactive-account rejection/mid-session deactivation), role-based authorization for every gated module, admin CRUD safety guards (delete-blocked-while-referenced, self-role-change/self-delete protection, stock math, low-stock notification de-duplication), branch-scoped staff access, and payments/analytics access boundaries.

## Roles & Permissions

| Role | Access |
|---|---|
| Super Admin | Everything, including Users management |
| Clinic Admin | Everything except Users management |
| Receptionist | Dashboard, Appointments, Patients, Notifications |
| Healthcare Staff | Dashboard, Appointments, Notifications |

Content modules (Services, Branches, Healthcare Team, Health Articles, Medicines, Inventory, Reports, Analytics, Website Settings) require the `manage-settings` ability (Super Admin + Clinic Admin). User management requires `manage-users` (Super Admin only). See `app/Providers/AppServiceProvider.php` for the gate definitions and `routes/web.php` for how they're applied.

**Branch scoping**: a Receptionist or Healthcare Staff user with a `branch_id` set (Admin → Users) only sees appointments and dashboard stats for that branch. Super Admin and Clinic Admin are always unscoped, regardless of any `branch_id` on their record — see `User::isBranchScoped()`.

## Patient Portal

Patients get a self-service account, separate from staff logins, under `/portal` (its own `patient` auth guard — see `config/auth.php`). Registering with a phone number that already has appointment history (booked via the public form) claims that existing patient record instead of creating a duplicate.

From the portal a patient can view their appointment history and status, submit mobile money/bank/cash payment details for an appointment, download a paid receipt as a PDF, and update their profile/password. Staff still confirm/reject payments and manage appointment status from the admin dashboard — the portal doesn't bypass that review step.

## Payments & Digital Receipts

`Admin → Payments` (and the Payments card on an appointment's admin detail page) records payments against an appointment: cash/mobile money/bank transfer/card, with a generated reference (`RCT-YYYYMMDD-XXXXX`). No real payment gateway is wired up — there's a `gateway` column defaulting to `manual` for exactly this reason; integrating a real provider (M-Pesa, Tigo Pesa, Stripe, etc.) later means adding a driver, not restructuring the schema.

Once a payment is marked **Paid**, both staff (`/admin/receipts/{payment}`) and the patient (`/portal/receipts/{payment}`, only for their own payments) can view/print an A5 PDF receipt (`barryvdh/laravel-dompdf`).

## SMS & WhatsApp Reminders

Appointment reminders use a driver interface (`App\Services\Reminders\{SmsGatewayInterface,WhatsAppGatewayInterface}`) so a real provider can be swapped in without touching calling code. By default both drivers are `log` — reminders are written to `storage/logs/reminders.log` (and recorded in the `reminders` table) instead of actually being sent, since no SMS/WhatsApp provider credentials exist for this clinic yet.

```
SMS_DRIVER=log
WHATSAPP_DRIVER=log
REMINDER_HOURS_BEFORE=24
```

To wire up a real provider, add a class implementing the relevant interface, register it in the `match()` in `AppServiceProvider::register()`, and point `SMS_DRIVER`/`WHATSAPP_DRIVER` at it.

Reminders can be sent manually from an appointment's admin detail page ("Send Reminder Now"), or automatically: `php artisan appointments:send-reminders` finds confirmed appointments starting in `REMINDER_HOURS_BEFORE` hours and sends one reminder per channel per day. It's scheduled daily at 09:00 (`routes/console.php`) — in production this needs the Laravel scheduler cron entry running:

```
* * * * * cd /path-to-app && php artisan schedule:run >> /dev/null 2>&1
```

## Reports & Analytics

`Admin → Reports` covers Appointments, Services, Branches, Inventory, Revenue (by payment method and by branch), and Patient Demographics (gender breakdown, new registrations) — each with a date-range filter and CSV export. `Admin → Analytics` adds a rolling 6-month dashboard: appointment/revenue trends, a Requested → Confirmed → Completed/Cancelled conversion funnel, and top services/branches. Both require the `manage-settings` ability.

## Appearance: Dark Mode

A manual light/dark toggle (sun/moon icon) sits in the public navbar, the admin topbar, the patient portal header, and the login/registration screens. The choice is stored in `localStorage` (defaulting to the visitor's OS preference on first visit) and applied before first paint via a small inline script (`resources/views/partials/theme-init.blade.php`) to avoid a flash of the wrong theme.

Implementation-wise, most of the app is themed globally in `resources/css/app.css` by re-skinning the small set of repeated neutral utility classes (`bg-white`, `bg-surface-*`, `text-gray-*`, `border-surface-*`) under a `.dark` scope, rather than hand-adding `dark:` classes to every one of the ~90 Blade views — brand colors (the primary green palette, status colors) and surfaces already designed dark (footer, admin sidebar) are left alone since they already read fine on a dark background.

## Language: English / Kiswahili

The public site, patient portal, and login/registration screens have an EN/SW switcher (next to the theme toggle). The choice is stored in the session (`App\Http\Middleware\SetLocale`) and applied via Laravel's standard `__()` translation helper — English strings are the literal keys in Blade, with `lang/sw.json` supplying the Kiswahili translation for each one. Adding a new translatable string just means wrapping it in `__('...')`; no new key needs inventing on the English side.

The admin (staff) dashboard is intentionally **not** translated — it's an internal back-office tool staff already use in English throughout this build, whereas the public site and patient portal are what local Tanzanian patients interact with directly. Admin-authored content (service descriptions, health articles, site settings text) is likewise not machine-translated, since that's real clinic-authored content, not UI chrome.

## Security Notes

- Passwords hashed with bcrypt; login is rate-limited per email+IP (5 attempts) with a friendly lockout message
- CSRF protection on every form (Laravel default); all queries go through Eloquent/query builder — no raw SQL with user input
- File uploads are restricted to `jpeg,jpg,png,webp` (`ico` also allowed for favicons) — **SVG is deliberately excluded**, since an uploaded SVG served back from `/storage` can carry an embedded script and be opened directly as a same-origin document
- Security headers (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`) are applied globally via `App\Http\Middleware\SecurityHeaders`. A Content-Security-Policy was deliberately **not** added — this app inlines Alpine directives and Blade-generated SVGs throughout, and a strict CSP needs a proper nonce/hash audit rather than a one-line bolt-on
- `APP_ENV=production` forces the URL scheme to `https` (see `AppServiceProvider::boot()`) — set `SESSION_SECURE_COOKIE=true` in production `.env` too (see checklist below)
- The public appointment-booking endpoint is rate-limited (`throttle:5,1`) against spam submissions
- A deactivated staff account is force-logged-out on its very next request (`EnsureUserIsActive` middleware) and is rejected at the login form itself, not just after establishing a session
- Sensitive actions (user created/role or status changed/deleted, website settings updated) are recorded in the `audit_logs` table via `App\Models\AuditLog::record()`. There's currently no dashboard page for viewing this log — it's a backend safety net (queryable via `php artisan tinker` or a DB client), not a new sidebar module, since one wasn't part of the original spec. Say the word if you'd like a viewer added.

## Deployment Checklist

```bash
composer install --no-dev --optimize-autoloader
npm run build

cp .env.example .env   # then fill in real production values — see below
php artisan key:generate

php artisan migrate --force
php artisan storage:link

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Production `.env` values that matter beyond the defaults:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.tld

SESSION_SECURE_COOKIE=true

ADMIN_EMAIL=<a real address you control>
ADMIN_PASSWORD=<a freshly generated strong password — rotate immediately after first login>

MAIL_MAILER=<a real transport — currently `log`, fine for dev only>

SMS_DRIVER=<a real driver once one is built — currently `log`, writes to storage/logs/reminders.log only>
WHATSAPP_DRIVER=<same>
```

Also:
- Ensure `storage/` and `bootstrap/cache/` are writable by the web server user
- Put the document root at `public/`, not the project root
- Terminate TLS at the load balancer/reverse proxy and confirm `X-Forwarded-Proto` is trusted (`config/trustedproxy` or `$middleware->trustProxies()` if you're behind one) so `URL::forceScheme('https')` and `SESSION_SECURE_COOKIE` behave correctly
- Point the logging channel at `daily` (`LOG_CHANNEL=daily`) with a retention policy instead of a single growing file
- If you change any config after deploy, re-run `php artisan config:cache` — cached config silently ignores new `.env` values otherwise

### Database backups

No backup package is bundled — a scheduled `mysqldump` is simpler to reason about and audit than adding a dependency for this. Example cron entry on the app server:

```bash
0 2 * * * mysqldump -u <user> -p'<password>' lifestyle_sanitarium | gzip > /var/backups/clinic/db-$(date +\%F).sql.gz
```

Rotate/prune old backups and copy them off-server (S3, another host, etc.) — a backup that lives next to the database it protects isn't a backup. Restore with:

```bash
gunzip < db-2026-08-06.sql.gz | mysql -u <user> -p lifestyle_sanitarium
```

## Project Structure Notes

- Public controllers live at the root of `App\Http\Controllers`; everything admin-only is under `App\Http\Controllers\Admin`
- Form Requests handle all validation (`App\Http\Requests`, `App\Http\Requests\Admin`) — controllers stay thin
- Blade components are split into `resources/views/components/` (shared: buttons, badges, forms, confirm dialogs) and `resources/views/components/admin/` (dashboard-only: sidebar, topbar, stat cards, stock-movement modal)
- `WebsiteSetting::allSettings()` is cached (`Cache::rememberForever`) and shared to every view via a composer in `AppServiceProvider` — it's invalidated automatically on save

## What's Built

Every module from the original build spec is live: public marketing site (Home/About/Services/Branches/Healthcare Team/Health Education/Contact), appointment booking with admin-side confirm/reschedule/cancel/complete workflow, role-based admin dashboard, Services/Branches/Healthcare Team/Health Articles CRUD, Patients & appointment history, Medicines & Inventory (stock in/out with a full audit trail, low-stock alerts), Reports (appointments/services/branches/inventory, CSV export), and in-app Notifications.

On top of that: a Patient Portal, Payments & Digital Receipts, SMS/WhatsApp reminder infrastructure, multi-branch staff scoping, Revenue/Patient Demographics reports, an Analytics dashboard, a light/dark mode toggle, and English/Kiswahili bilingual support across the public site and patient portal — see the sections above for each.
