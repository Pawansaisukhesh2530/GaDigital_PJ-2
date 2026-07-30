<?php
/**
 * Enquiries data-access + validation layer (PDO / prepared statements).
 * Used by the public contact form and the admin Enquiries module.
 */

/* =============================================================
 *  Validation
 * ============================================================= */

/**
 * Validate + normalise a contact submission.
 * Returns ['data' => [...clean...], 'errors' => ['field' => 'message']].
 * Note: values are trimmed here; escape on OUTPUT (never store escaped data).
 */
function enquiry_validate(array $post): array
{
    $errors = [];
    $data = [
        'name'    => trim($post['fullName'] ?? $post['name'] ?? ''),
        'email'   => trim($post['email'] ?? ''),
        'phone'   => trim($post['phone'] ?? ''),
        'message' => trim($post['message'] ?? ''),
    ];

    // Name
    if ($data['name'] === '') {
        $errors['fullName'] = 'Please enter your name.';
    } elseif (mb_strlen($data['name']) > 100) {
        $errors['fullName'] = 'Name is too long (max 100 characters).';
    }

    // Email
    if ($data['email'] === '') {
        $errors['email'] = 'Please enter your email address.';
    } elseif (!is_email($data['email']) || mb_strlen($data['email']) > 190) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Phone (optional)
    if ($data['phone'] !== '' && mb_strlen($data['phone']) > 40) {
        $errors['phone'] = 'Phone number is too long.';
    }

    // Message
    if ($data['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    } elseif (mb_strlen($data['message']) > 2000) {
        $errors['message'] = 'Message is too long (max 2000 characters).';
    }

    return ['data' => $data, 'errors' => $errors];
}

/* =============================================================
 *  Writes
 * ============================================================= */

function enquiry_create(array $d, string $ip = ''): int
{
    $st = db()->prepare(
        'INSERT INTO enquiries (name, email, phone, message, ip_address, is_read, created_at)
         VALUES (:name, :email, :phone, :message, :ip, 0, datetime(\'now\'))'
    );
    $st->execute([
        ':name'    => $d['name'],
        ':email'   => $d['email'],
        ':phone'   => $d['phone'] ?? '',
        ':message' => $d['message'],
        ':ip'      => $ip,
    ]);
    return (int) db()->lastInsertId();
}

function enquiry_mark_read(int $id, bool $read = true): void
{
    $st = db()->prepare('UPDATE enquiries SET is_read = :r WHERE id = :id');
    $st->execute([':r' => $read ? 1 : 0, ':id' => $id]);
}

function enquiry_delete(int $id): void
{
    db()->prepare('DELETE FROM enquiries WHERE id = :id')->execute([':id' => $id]);
}

/* =============================================================
 *  Reads
 * ============================================================= */

/** Admin listing with optional search + read/unread filter. */
function enquiries_list(array $filters = []): array
{
    $where = [];
    $params = [];

    $search = trim($filters['search'] ?? '');
    if ($search !== '') {
        $where[] = '(name LIKE :q OR email LIKE :q OR phone LIKE :q OR message LIKE :q)';
        $params[':q'] = '%' . $search . '%';
    }
    $read = $filters['read'] ?? '';
    if ($read === '0' || $read === '1') {
        $where[] = 'is_read = :r';
        $params[':r'] = (int) $read;
    }

    $sql = 'SELECT * FROM enquiries';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY datetime(created_at) DESC, id DESC';

    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

function enquiry_find(int $id): ?array
{
    $s = db()->prepare('SELECT * FROM enquiries WHERE id = :id');
    $s->execute([':id' => $id]);
    return $s->fetch() ?: null;
}

function enquiry_counts(): array
{
    return [
        'total'  => (int) db()->query('SELECT COUNT(*) FROM enquiries')->fetchColumn(),
        'unread' => (int) db()->query('SELECT COUNT(*) FROM enquiries WHERE is_read = 0')->fetchColumn(),
    ];
}
