-- ============================================================
--  Nivi Homes - SQLite schema
--  Uses PDO from the app.
-- ============================================================

PRAGMA foreign_keys = ON;

-- ---------- Admins ----------
CREATE TABLE IF NOT EXISTS admins (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    username            TEXT    NOT NULL UNIQUE,
    display_name        TEXT    NOT NULL DEFAULT '',
    email               TEXT    NOT NULL,
    password_hash       TEXT    NOT NULL,
    last_login_at       TEXT,
    password_changed_at TEXT,
    created_at          TEXT    NOT NULL DEFAULT (datetime('now')),
    updated_at          TEXT    NOT NULL DEFAULT (datetime('now'))
);

-- ---------- Admin one-time verification codes (OTP) ----------
-- Channel-agnostic (email now; sms can be added later). Codes are hashed.
CREATE TABLE IF NOT EXISTS admin_otps (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    admin_id   INTEGER NOT NULL,
    purpose    TEXT    NOT NULL,               -- 'password_change' | 'email_change'
    channel    TEXT    NOT NULL DEFAULT 'email',
    code_hash  TEXT    NOT NULL,
    payload    TEXT    NOT NULL DEFAULT '',     -- e.g. the pending new email
    expires_at TEXT    NOT NULL,
    used_at    TEXT,
    created_at TEXT    NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_otps_admin ON admin_otps(admin_id, purpose);

-- ---------- Projects ----------
CREATE TABLE IF NOT EXISTS projects (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    title             TEXT    NOT NULL,
    slug              TEXT    NOT NULL UNIQUE,
    location          TEXT    NOT NULL DEFAULT '',
    building_type     TEXT    NOT NULL DEFAULT '',
    build_up_area     TEXT    NOT NULL DEFAULT '',
    short_description TEXT    NOT NULL DEFAULT '',
    description       TEXT    NOT NULL DEFAULT '',
    cover_image       TEXT    NOT NULL DEFAULT '',
    status            TEXT    NOT NULL DEFAULT 'published',  -- published | draft
    is_featured       INTEGER NOT NULL DEFAULT 0,            -- 0 | 1
    display_order     INTEGER NOT NULL DEFAULT 0,
    created_at        TEXT    NOT NULL DEFAULT (datetime('now')),
    updated_at        TEXT    NOT NULL DEFAULT (datetime('now'))
);
CREATE INDEX IF NOT EXISTS idx_projects_status ON projects(status);
CREATE INDEX IF NOT EXISTS idx_projects_order  ON projects(display_order);

-- ---------- Project gallery images ----------
CREATE TABLE IF NOT EXISTS project_images (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    filename   TEXT    NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    created_at TEXT    NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_images_project ON project_images(project_id);

-- ---------- Project features / amenities ----------
CREATE TABLE IF NOT EXISTS project_features (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    feature    TEXT    NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_features_project ON project_features(project_id);

-- ---------- Contact enquiries ----------
CREATE TABLE IF NOT EXISTS enquiries (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    name       TEXT    NOT NULL,
    email      TEXT    NOT NULL,
    phone      TEXT    NOT NULL DEFAULT '',
    message    TEXT    NOT NULL DEFAULT '',
    ip_address TEXT    NOT NULL DEFAULT '',
    is_read    INTEGER NOT NULL DEFAULT 0,   -- 0 | 1
    created_at TEXT    NOT NULL DEFAULT (datetime('now'))
);
CREATE INDEX IF NOT EXISTS idx_enquiries_read ON enquiries(is_read);

-- ---------- Settings (key/value) ----------
CREATE TABLE IF NOT EXISTS settings (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key   TEXT NOT NULL UNIQUE,
    setting_value TEXT NOT NULL DEFAULT ''
);
