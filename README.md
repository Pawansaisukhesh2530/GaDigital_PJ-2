# Nivi Homes

A custom-built marketing website and admin panel for **Nivi Homes**, a home
builder. Built with plain PHP (no framework) and a file-based **SQLite**
database, so it runs anywhere PHP does — no database server required.

---

## Overview

The public site presents the company, its services, home designs and completed
projects, and lets visitors send enquiries through a contact form. A secure
admin panel manages projects, enquiries, site settings and the administrator
account. All company details, social links, Google Maps and email/SMTP
configuration are editable from the admin panel — no code changes needed.

---

## Features

**Public website**
- Home, About, Why Build With Us, Inclusions, Services (+ service detail pages)
- Home Designs (single & double storey) with individual design detail pages
- Projects listing + project detail pages with image galleries
- Contact page with a validated enquiry form and an embedded Google Map
- Fully responsive (desktop, tablet, mobile) with subtle scroll animations

**Admin panel** (`/admin`)
- Secure login (hashed passwords, hardened sessions, 30-min idle timeout)
- Dashboard with project & enquiry stats
- Projects CRUD: cover image, drag-and-drop gallery, features, drag-to-reorder
- Enquiries: list, search, read/unread, view, delete
- Settings: company info, contact details, social links, Google Maps, and
  **Email/SMTP configuration** with a "Send Test Email" button
- My Account: change username/display name, email (email-OTP verified) and
  password (email-OTP verified)

**Security**
- PDO prepared statements everywhere
- CSRF protection on every form
- Output escaping, input validation
- `password_hash` / `password_verify`, one-time codes for sensitive changes
- `.htaccess` deny rules protecting `data/`, `app/` and `database/`

---

## Folder structure

```
GaDigital_PJ-2/
├── admin/                 Admin panel (login, dashboard, projects, enquiries, settings, account)
│   ├── assets/            admin.css, admin.js
│   └── partials/          header, sidebar, topbar, footer
├── app/                   Backend logic
│   ├── config.php         Paths & constants
│   ├── config.mail.php    Legacy SMTP fallback (kept blank; real config lives in Settings)
│   ├── bootstrap.php      Session + core bootstrap
│   ├── db.php             PDO/SQLite connection
│   ├── auth.php           Authentication
│   ├── helpers.php        e(), csrf, flash, validation helpers
│   ├── projects.php       Projects data layer
│   ├── enquiries.php      Enquiries data layer
│   ├── settings.php       Settings data layer
│   ├── account.php        Admin account data layer
│   ├── otp.php            One-time code service (email; SMS-ready)
│   ├── mail.php           PHPMailer wrapper (reads SMTP from Settings)
│   ├── upload.php         Image upload handling
│   └── lib/PHPMailer/     Bundled PHPMailer (no Composer dependency required)
├── assets/                css, js, images, pdfs, uploads/projects
├── data/                  SQLite DB + runtime (sessions, logs) — protected by .htaccess
│   └── nivihomes.sqlite   The database (ships with demo data)
├── database/              schema.sql, install.php, seed_projects.php, reset_projects.php
├── includes/              Public site config, header, footer, navbar, banner
├── *.php                  Public pages (index, about, services, contact, ...)
├── composer.json
├── README.md
└── SETUP_GUIDE.md
```

---

## Installation

**Requirements:** PHP 8.0+ with `pdo_sqlite`, `mbstring`, `fileinfo` and
`openssl` extensions. (XAMPP on Windows includes all of these.)

```bash
git clone https://github.com/Pawansaisukhesh2530/GaDigital_PJ-2.git
cd GaDigital_PJ-2
```

The repository already includes a ready-to-use `data/nivihomes.sqlite` with the
default admin, default settings and demo projects, so you can run it straight
away. To rebuild the database from scratch, see **Database setup** below.

### Composer installation (optional)

PHPMailer is **bundled** in `app/lib/PHPMailer/`, so Composer is **not required**
to run the site. `composer.json` is provided for metadata and to declare the PHP
version/extensions. If you use Composer:

```bash
composer install
```

---

## Database setup

The database is a single SQLite file at `data/nivihomes.sqlite`.

To (re)create a fresh database from the schema and seed the default admin +
settings:

```bash
php database/install.php
```

To load the demo projects and their images (optional, only if the DB has no
projects yet):

```bash
php database/seed_projects.php
```

`install.php` is safe to re-run — it won't duplicate the admin or settings and
will add any missing columns/keys.

---

## SMTP configuration

Email (contact-form notifications and account verification codes) is configured
entirely from the admin panel — **no code editing and no credentials in the
repository**.

1. Log in to `/admin` and open **Settings → Email Settings**.
2. Enter your SMTP host, port, encryption, username and password, set a
   "From Name" and the **Enquiry Email** (where contact submissions are sent).
3. Tick **Enable email sending (SMTP)** and **Save**.
4. Click **Send Test Email** to confirm it works.

For Gmail / Google Workspace, use `smtp.gmail.com`, port `587`, TLS, your full
email as the username, and a **Google App Password** (not your login password).

While SMTP is disabled or not configured, enquiries are still saved to the
database and verification codes are written to `data/logs/mail.log`, so nothing
breaks during local development.

---

## Admin login

```
URL:      /admin
Username: admin
Password: Admin@123
```

Change the password immediately after first login via **My Account** (verified
by a one-time code emailed to the account address).

---

## Running the project

Using PHP's built-in server (from the project root):

```bash
php -S localhost:8000
```

Then open:
- Public site: <http://localhost:8000/>
- Admin panel: <http://localhost:8000/admin>

On XAMPP you can instead place the folder under `htdocs/` and browse to it via
Apache.

---

## Common issues

| Problem | Fix |
|---|---|
| "could not find driver" | Enable `pdo_sqlite` in `php.ini`. |
| Admin login redirects back to login | Ensure `data/` is writable (sessions are stored in `data/sessions`). |
| Uploaded images not saving | Ensure `assets/uploads/projects/` is writable. |
| Test email fails | Re-check SMTP settings; Gmail needs an **App Password**. Errors are logged to `data/logs/mail.log`. |
| Map not showing on Contact | In Settings use a Google Maps **Embed** URL (Share → Embed a map), not a share link. |
| Blank page / 500 | Confirm PHP 8.0+ and the required extensions are enabled. |
