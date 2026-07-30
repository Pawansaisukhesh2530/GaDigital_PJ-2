<?php
/**
 * Projects data-access layer (PDO / prepared statements).
 * Shared by the admin panel and the public frontend.
 */
require_once __DIR__ . '/upload.php';

/* =============================================================
 *  Reads
 * ============================================================= */

/** Admin listing with optional search / status / featured filters. */
function projects_list(array $filters = []): array
{
    $where = [];
    $params = [];

    $search = trim($filters['search'] ?? '');
    if ($search !== '') {
        $where[] = '(title LIKE :q OR location LIKE :q OR slug LIKE :q)';
        $params[':q'] = '%' . $search . '%';
    }
    $status = $filters['status'] ?? '';
    if ($status === 'published' || $status === 'draft') {
        $where[] = 'status = :status';
        $params[':status'] = $status;
    }
    $featured = $filters['featured'] ?? '';
    if ($featured === '1' || $featured === '0') {
        $where[] = 'is_featured = :featured';
        $params[':featured'] = (int) $featured;
    }

    $sql = 'SELECT * FROM projects';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY display_order ASC, datetime(created_at) DESC, id DESC';

    $st = db()->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
}

/** All published projects for the public grid, in display order. */
function projects_published(): array
{
    return db()->query(
        "SELECT * FROM projects WHERE status = 'published'
          ORDER BY display_order ASC, datetime(created_at) DESC, id DESC"
    )->fetchAll();
}

function project_find(int $id): ?array
{
    $s = db()->prepare('SELECT * FROM projects WHERE id = :id');
    $s->execute([':id' => $id]);
    return $s->fetch() ?: null;
}

/** Public lookup by slug (published only). */
function project_by_slug(string $slug): ?array
{
    $s = db()->prepare("SELECT * FROM projects WHERE slug = :s AND status = 'published'");
    $s->execute([':s' => $slug]);
    return $s->fetch() ?: null;
}

function project_images(int $projectId): array
{
    $s = db()->prepare('SELECT * FROM project_images WHERE project_id = :p ORDER BY sort_order ASC, id ASC');
    $s->execute([':p' => $projectId]);
    return $s->fetchAll();
}

function project_image_find(int $imageId): ?array
{
    $s = db()->prepare('SELECT * FROM project_images WHERE id = :i');
    $s->execute([':i' => $imageId]);
    return $s->fetch() ?: null;
}

function project_features(int $projectId): array
{
    $s = db()->prepare('SELECT * FROM project_features WHERE project_id = :p ORDER BY sort_order ASC, id ASC');
    $s->execute([':p' => $projectId]);
    return $s->fetchAll();
}

function project_feature_find(int $featureId): ?array
{
    $s = db()->prepare('SELECT * FROM project_features WHERE id = :i');
    $s->execute([':i' => $featureId]);
    return $s->fetch() ?: null;
}

/** Dashboard counters. */
function project_counts(): array
{
    return [
        'total'     => (int) db()->query('SELECT COUNT(*) FROM projects')->fetchColumn(),
        'featured'  => (int) db()->query('SELECT COUNT(*) FROM projects WHERE is_featured = 1')->fetchColumn(),
        'published' => (int) db()->query("SELECT COUNT(*) FROM projects WHERE status = 'published'")->fetchColumn(),
        'draft'     => (int) db()->query("SELECT COUNT(*) FROM projects WHERE status = 'draft'")->fetchColumn(),
    ];
}

function project_recent(int $limit = 5): array
{
    $s = db()->prepare('SELECT * FROM projects ORDER BY datetime(created_at) DESC, id DESC LIMIT :l');
    $s->bindValue(':l', $limit, PDO::PARAM_INT);
    $s->execute();
    return $s->fetchAll();
}

/* =============================================================
 *  Slug + validation
 * ============================================================= */

function project_unique_slug(string $slug, ?int $ignoreId = null): string
{
    $slug = slugify($slug);
    $base = $slug;
    $i = 2;
    while (true) {
        $sql = 'SELECT COUNT(*) FROM projects WHERE slug = :s' . ($ignoreId ? ' AND id != :id' : '');
        $st  = db()->prepare($sql);
        $p = [':s' => $slug];
        if ($ignoreId) {
            $p[':id'] = $ignoreId;
        }
        $st->execute($p);
        if ((int) $st->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i++;
    }
}

/**
 * Validate + normalise submitted project fields.
 * Returns ['data' => array, 'errors' => array].
 */
function project_validate(array $post): array
{
    $errors = [];
    $data = [
        'title'             => trim($post['title'] ?? ''),
        'slug'              => trim($post['slug'] ?? ''),
        'location'          => trim($post['location'] ?? ''),
        'building_type'     => trim($post['building_type'] ?? ''),
        'build_up_area'     => trim($post['build_up_area'] ?? ''),
        'short_description' => trim($post['short_description'] ?? ''),
        'description'       => trim($post['description'] ?? ''),
        'status'            => ($post['status'] ?? 'published') === 'draft' ? 'draft' : 'published',
        'is_featured'       => !empty($post['is_featured']) ? 1 : 0,
    ];

    if ($data['title'] === '') {
        $errors['title'] = 'Title is required.';
    } elseif (mb_strlen($data['title']) > 200) {
        $errors['title'] = 'Title is too long (max 200 characters).';
    }

    return ['data' => $data, 'errors' => $errors];
}

/* =============================================================
 *  Writes
 * ============================================================= */

function project_create(array $d): int
{
    // New projects always go to the bottom of the list (next display_order).
    $nextOrder = (int) db()->query('SELECT COALESCE(MAX(display_order), 0) + 1 FROM projects')->fetchColumn();

    $st = db()->prepare(
        'INSERT INTO projects
            (title, slug, location, building_type, build_up_area,
             short_description, description, cover_image, status,
             is_featured, display_order, created_at, updated_at)
         VALUES
            (:title, :slug, :location, :building_type, :build_up_area,
             :short_description, :description, :cover_image, :status,
             :is_featured, :display_order, datetime(\'now\'), datetime(\'now\'))'
    );
    $st->execute([
        ':title'             => $d['title'],
        ':slug'              => $d['slug'],
        ':location'          => $d['location'] ?? '',
        ':building_type'     => $d['building_type'] ?? '',
        ':build_up_area'     => $d['build_up_area'] ?? '',
        ':short_description' => $d['short_description'] ?? '',
        ':description'       => $d['description'] ?? '',
        ':cover_image'       => $d['cover_image'] ?? '',
        ':status'            => $d['status'] ?? 'published',
        ':is_featured'       => !empty($d['is_featured']) ? 1 : 0,
        ':display_order'     => $nextOrder,
    ]);
    return (int) db()->lastInsertId();
}

function project_update(int $id, array $d): void
{
    // display_order is intentionally NOT updated here - ordering is managed
    // exclusively via drag-and-drop on the list page (project_reorder()).
    $st = db()->prepare(
        'UPDATE projects SET
            title = :title, slug = :slug, location = :location,
            building_type = :building_type, build_up_area = :build_up_area,
            short_description = :short_description, description = :description,
            status = :status, is_featured = :is_featured,
            updated_at = datetime(\'now\')
         WHERE id = :id'
    );
    $st->execute([
        ':title'             => $d['title'],
        ':slug'              => $d['slug'],
        ':location'          => $d['location'] ?? '',
        ':building_type'     => $d['building_type'] ?? '',
        ':build_up_area'     => $d['build_up_area'] ?? '',
        ':short_description' => $d['short_description'] ?? '',
        ':description'       => $d['description'] ?? '',
        ':status'            => $d['status'] ?? 'published',
        ':is_featured'       => !empty($d['is_featured']) ? 1 : 0,
        ':id'                => $id,
    ]);
}

/** Apply a new project order given an array of project ids (first = position 1). */
function project_reorder(array $orderedIds): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare('UPDATE projects SET display_order = :o, updated_at = datetime(\'now\') WHERE id = :id');
        $pos = 1;
        foreach (array_values($orderedIds) as $pid) {
            $st->execute([':o' => $pos++, ':id' => (int) $pid]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function project_set_cover(int $id, string $filename): void
{
    $st = db()->prepare('UPDATE projects SET cover_image = :c, updated_at = datetime(\'now\') WHERE id = :id');
    $st->execute([':c' => $filename, ':id' => $id]);
}

/** Delete a project and all its assets (image files + cascaded rows). */
function project_delete(int $id): void
{
    $project = project_find($id);
    if (!$project) {
        return;
    }
    foreach (project_images($id) as $img) {
        upload_delete($img['filename']);
    }
    if (!empty($project['cover_image'])) {
        upload_delete($project['cover_image']);
    }
    db()->prepare('DELETE FROM projects WHERE id = :id')->execute([':id' => $id]);
}

/* =============================================================
 *  Gallery images
 * ============================================================= */

function project_image_add(int $projectId, string $filename): int
{
    $s = db()->prepare('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM project_images WHERE project_id = :p');
    $s->execute([':p' => $projectId]);
    $order = (int) $s->fetchColumn();

    $st = db()->prepare(
        'INSERT INTO project_images (project_id, filename, sort_order, created_at)
         VALUES (:p, :f, :o, datetime(\'now\'))'
    );
    $st->execute([':p' => $projectId, ':f' => $filename, ':o' => $order]);
    return (int) db()->lastInsertId();
}

/** Delete one gallery image (file + row) by id. */
function project_image_delete(int $imageId): void
{
    $img = project_image_find($imageId);
    if (!$img) {
        return;
    }
    upload_delete($img['filename']);
    db()->prepare('DELETE FROM project_images WHERE id = :i')->execute([':i' => $imageId]);
}

/** Apply a new order given an array of image ids (first = position 0). */
function project_images_reorder(int $projectId, array $orderedIds): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare('UPDATE project_images SET sort_order = :o WHERE id = :i AND project_id = :p');
        foreach (array_values($orderedIds) as $pos => $imgId) {
            $st->execute([':o' => $pos, ':i' => (int) $imgId, ':p' => $projectId]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/* =============================================================
 *  Features / amenities
 * ============================================================= */

function project_feature_add(int $projectId, string $feature): int
{
    $feature = trim($feature);
    if ($feature === '') {
        return 0;
    }
    $s = db()->prepare('SELECT COALESCE(MAX(sort_order), -1) + 1 FROM project_features WHERE project_id = :p');
    $s->execute([':p' => $projectId]);
    $order = (int) $s->fetchColumn();

    $st = db()->prepare('INSERT INTO project_features (project_id, feature, sort_order) VALUES (:p, :f, :o)');
    $st->execute([':p' => $projectId, ':f' => $feature, ':o' => $order]);
    return (int) db()->lastInsertId();
}

function project_feature_update(int $featureId, string $feature): void
{
    $feature = trim($feature);
    if ($feature === '') {
        return;
    }
    db()->prepare('UPDATE project_features SET feature = :f WHERE id = :i')
        ->execute([':f' => $feature, ':i' => $featureId]);
}

function project_feature_delete(int $featureId): void
{
    db()->prepare('DELETE FROM project_features WHERE id = :i')->execute([':i' => $featureId]);
}

/** Move a feature up/down by swapping sort_order with its neighbour. */
function project_feature_move(int $projectId, int $featureId, string $dir): void
{
    $features = project_features($projectId);
    $idx = null;
    foreach ($features as $i => $f) {
        if ((int) $f['id'] === $featureId) {
            $idx = $i;
            break;
        }
    }
    if ($idx === null) {
        return;
    }
    $swap = $dir === 'up' ? $idx - 1 : $idx + 1;
    if ($swap < 0 || $swap >= count($features)) {
        return;
    }
    $a = $features[$idx];
    $b = $features[$swap];

    $pdo = db();
    $pdo->beginTransaction();
    try {
        $st = $pdo->prepare('UPDATE project_features SET sort_order = :o WHERE id = :i');
        $st->execute([':o' => (int) $b['sort_order'], ':i' => (int) $a['id']]);
        $st->execute([':o' => (int) $a['sort_order'], ':i' => (int) $b['id']]);
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
