# Nivi Homes — Setup Guide (Fresh Windows Laptop)

This guide walks you through cloning and running the project on a brand-new
Windows machine, from installing the software to managing content. Follow it
top to bottom and the site will run without any extra instructions.

---

## 1. Software requirements

| Software | Version | Purpose |
|---|---|---|
| PHP | **8.0 or higher** (8.2 recommended) | Runs the application |
| SQLite | Bundled with PHP (`pdo_sqlite`) | Database (no server needed) |
| Git | Latest | Clone the repository |
| Composer | Latest (optional) | Only for metadata; dependencies are bundled |

The easiest way to get PHP + SQLite on Windows is **XAMPP**
(<https://www.apachefriends.org/>), which ships PHP 8.2 with all required
extensions.

### Required PHP extensions
`pdo`, `pdo_sqlite`, `mbstring`, `fileinfo`, `openssl`.
These are enabled by default in XAMPP. To confirm:

```bat
php -m
```

Look for `pdo_sqlite`, `mbstring`, `fileinfo`, `openssl` in the list.

> If `php` is not recognised, add PHP to your PATH or call it by full path,
> e.g. `C:\xampp\php\php.exe`.

---

## 2. Install the tools

1. **Install XAMPP** (or standalone PHP 8.2). Note the PHP path, e.g.
   `C:\xampp\php\php.exe`.
2. **Install Git**: <https://git-scm.com/download/win>.
3. *(Optional)* **Install Composer**: <https://getcomposer.org/download/>.

---

## 3. Clone the repository

```bat
cd %USERPROFILE%\Desktop
git clone https://github.com/Pawansaisukhesh2530/GaDigital_PJ-2.git
cd GaDigital_PJ-2
```

If the PHP application lives in a `nivi-homes` sub-folder, `cd` into it:

```bat
cd nivi-homes
```

---

## 4. Composer install (optional)

Dependencies (PHPMailer) are already bundled, so this step is optional:

```bat
composer install
```

The site runs fine without it.

---

## 5. Database setup

The repo ships with a ready `data\nivihomes.sqlite` (default admin, default
settings, 5 demo projects), so you can skip straight to running it.

To rebuild the database from scratch instead:

```bat
C:\xampp\php\php.exe database\install.php
C:\xampp\php\php.exe database\seed_projects.php
```

- `install.php` creates the schema and seeds the default admin + settings.
- `seed_projects.php` loads the 5 demo projects and their images.

Make sure the `data\` folder is **writable** (it holds the database, sessions
and logs).

---

## 6. Run the project

From inside the `nivi-homes` folder:

```bat
C:\xampp\php\php.exe -S localhost:8000
```

Open in your browser:
- Public site: <http://localhost:8000/>
- Admin panel: <http://localhost:8000/admin>

(Alternatively, copy the folder into `C:\xampp\htdocs\` and browse via Apache.)

---

## 7. Default admin login

```
URL:      http://localhost:8000/admin
Username: admin
Password: Admin@123
```

**Change this password after first login** (My Account → Change Password).

---

## 8. SMTP configuration (sending email)

All email settings are managed in the admin panel — nothing to edit in code.

1. Go to **Settings → Email Settings**.
2. Fill in:
   - **SMTP Host** — e.g. `smtp.gmail.com`
   - **SMTP Port** — `587` (TLS) or `465` (SSL)
   - **Encryption** — `TLS`
   - **SMTP Username** — your full email address
   - **SMTP Password** — a **Google App Password** for Gmail/Workspace
     (create at <https://myaccount.google.com/apppasswords>)
   - **From Name** — e.g. `Nivi Homes`
   - **Enquiry Email** — where contact-form messages are delivered
   - **Reply-To** — `Visitor's email` (recommended)
3. Tick **Enable email sending (SMTP)** and **Save**.
4. Click **Send Test Email** and check the inbox.

If SMTP is left disabled, enquiries are still saved and verification codes are
written to `data\logs\mail.log`.

---

## 9. Changing company information

**Settings** lets you edit, and have it reflected instantly across the whole
site (header, footer, contact page):

- Company name, company email, phone numbers, address, business hours
- Social media links (Facebook, Instagram, X, LinkedIn, YouTube, Pinterest)
- Google Maps URL (address link) and Google Maps **Embed** URL (the map iframe)
  - For the embed: in Google Maps choose **Share → Embed a map** and copy the
    URL inside `src="…"`. A `maps.app.goo.gl` share link will not embed.

---

## 10. Managing projects

**Projects** in the admin panel:
- **Add Project** — title, location, building type, area, description, cover image.
- After creating, add **gallery images** (drag to reorder) and **features**.
- **Reorder** projects by dragging rows on the list page — the public site
  reflects the order immediately.
- Publish/Draft and Featured toggles control visibility.

---

## 11. Managing enquiries

**Enquiries** in the admin panel:
- View all contact-form submissions, newest first.
- Search, filter by read/unread, open to read (auto-marks read), mark unread,
  or delete.
- If SMTP is configured, each submission is also emailed to the Enquiry Email
  with the visitor set as Reply-To.

---

## 12. Troubleshooting

| Symptom | Cause / Fix |
|---|---|
| `could not find driver` | Enable `extension=pdo_sqlite` in `php.ini`, restart. |
| Login loops back to the login page | `data\sessions` not writable — make `data\` writable. |
| Images won't upload | Make `assets\uploads\projects\` writable; check the 5 MB limit. |
| Test email fails | Verify SMTP details; Gmail needs an **App Password**; see `data\logs\mail.log`. |
| Contact map is blank | Use a Google Maps **Embed** URL, not a share/normal link. |
| 500 / blank page | Confirm PHP 8.0+ and required extensions; check the PHP error log. |
| Forgot admin password | Re-run `php database\install.php` on a fresh DB, or reset the `admins` row. |

---

You're set. Clone → (optionally build DB) → `php -S localhost:8000` → log in at
`/admin` with `admin` / `Admin@123`, then configure SMTP and company details
from **Settings**.
