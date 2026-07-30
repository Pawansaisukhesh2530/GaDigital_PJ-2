# Deploying Nivi Homes on Render

This guide covers deploying the Nivi Homes PHP project as a **Web Service** on
[Render](https://render.com) using Docker.

---

## Quick Start

1. Push this repository to GitHub (already done).
2. Go to <https://dashboard.render.com/> → **New** → **Web Service**.
3. Connect your GitHub repo: `Pawansaisukhesh2530/GaDigital_PJ-2`.
4. Configure using the settings below.
5. Click **Create Web Service**.

---

## Render Settings

| Setting | Value |
|---------|-------|
| **Name** | `nivi-homes` (or your preference) |
| **Region** | Choose closest to your audience (e.g. Singapore, Oregon) |
| **Runtime** | **Docker** |
| **Branch** | `main` |
| **Dockerfile Path** | `./Dockerfile` |
| **Plan** | Free or Starter |

### Environment Variables

| Variable | Value | Notes |
|----------|-------|-------|
| `PORT` | `10000` | Render sets this automatically — do NOT change |

No other environment variables are required. SMTP credentials are configured
from the admin panel after deployment (stored in the SQLite database).

### Build & Start Commands

When using Docker runtime, Render uses the Dockerfile directly:
- **Build command:** (handled by Docker — leave blank)
- **Start command:** (handled by Dockerfile CMD — leave blank)

---

## What Happens at Build Time

1. Docker pulls `php:8.2-apache`.
2. Installs `pdo_sqlite`, `mbstring`, `fileinfo` extensions.
3. Configures Apache to listen on `$PORT` (Render injects this).
4. Copies the project files into the container.
5. Sets correct permissions on `data/` and `assets/uploads/`.
6. Runs `database/install.php` to ensure the SQLite DB has schema + admin.
7. Apache starts and serves the site.

---

## After Deployment

1. Visit `https://your-app.onrender.com/admin`
2. Log in with `admin` / `Admin@123`
3. **Change the password immediately** (My Account → Change Password)
4. Configure SMTP in **Settings → Email Settings**
5. Update company info, social links, and Google Maps in **Settings**

---

## Important: SQLite Persistence on Render

**Render's free and starter plans use ephemeral filesystems.** This means:

- The SQLite database (`data/nivihomes.sqlite`) lives inside the container.
- On each new deploy, the filesystem resets to the Docker image state.
- Data written after deployment (new projects, enquiries, uploaded images)
  **will be lost on the next deploy or restart**.

### Workarounds

| Option | Difficulty | Notes |
|--------|-----------|-------|
| **Render Persistent Disk** (recommended) | Easy | Attach a disk, mount at `/var/www/html/data`. Available on paid plans ($0.25/GB/month). Uploaded images still need a separate solution. |
| **Mount disk at `/var/www/html/data` AND `/var/www/html/assets/uploads`** | Easy | Preserves both DB and uploads. Requires two mount points or restructuring uploads into `data/`. |
| **External object storage (S3/Cloudflare R2)** | Medium | For uploaded images. Would require code changes to the upload system. |
| **Switch to PostgreSQL** | Hard | Render offers managed PostgreSQL. Would require rewriting all SQLite queries. Not recommended unless scaling is needed. |

### Recommended Production Setup (Paid Plan)

1. Create a **Persistent Disk** on Render (1 GB is plenty).
2. Mount it at `/var/www/html/data`.
3. On first deploy, the `database/install.php` creates the DB on the
   persistent disk — it survives future deploys.
4. For uploaded images, either:
   - Mount a second disk at `/var/www/html/assets/uploads/projects`, OR
   - Accept that uploads reset on deploy (re-upload from admin after each deploy).

### For Demo / Portfolio Use (Free Plan)

The free plan works perfectly for showcasing the site. The pre-seeded database
and demo project images are baked into the Docker image at build time, so the
site always looks complete. Just be aware that any changes made through the
admin panel will not persist across deploys.

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Site shows 500 error | Check Render logs. Usually a missing PHP extension or permission issue. |
| Login redirects back to login | Session directory not writable. The Dockerfile handles this, but verify `data/sessions/` permissions. |
| Images not uploading | Verify `assets/uploads/projects/` is writable (Dockerfile sets this). |
| Database errors | Ensure `data/` is writable and `install.php` ran successfully during build. |
| SMTP not working | Configure SMTP from admin Settings after deployment. Check `data/logs/mail.log`. |
| Contact map blank | Enter a Google Maps **Embed** URL in Settings (not a share link). |

---

## File Summary

| File | Purpose |
|------|---------|
| `Dockerfile` | Production Docker image (PHP 8.2 + Apache + SQLite) |
| `.dockerignore` | Excludes unnecessary files from the Docker build |
| `DEPLOY_RENDER.md` | This file — deployment instructions |

---

## Tech Stack

- **Runtime:** PHP 8.2 + Apache 2.4
- **Database:** SQLite 3 (file-based, no external DB server)
- **Email:** PHPMailer (bundled, SMTP configured from admin panel)
- **Hosting:** Render Web Service (Docker)
