<?php
/**
 * Seed the original (hardcoded) projects into the database and migrate their
 * images into assets/uploads/projects/. Idempotent: skips if projects exist.
 *
 * Run:  php database/seed_projects.php
 */
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/projects.php';

$line = fn(string $s) => print($s . PHP_EOL);

if ((int) db()->query('SELECT COUNT(*) FROM projects')->fetchColumn() > 0) {
    $line('Projects already exist - skipping project seed.');
    return;
}

$IMG = dirname(__DIR__) . '/assets/images';

$seed = [
    [
        'slug' => 'rustum-20',
        'title' => 'RUSTUM 20 Halifax St ,Nirimbafields NSW',
        'location' => '20 Halifax St, Nirimba Fields, NSW',
        'building_type' => 'High end Luxury home',
        'build_up_area' => '34 sqs',
        'cover' => $IMG . '/project-tours/rustum.jpg',
        'gallery_dir' => $IMG . '/projects/rustum-20', 'count' => 20,
        'order' => 1,
    ],
    [
        'slug' => 'nirvana',
        'title' => 'Project Nirvana',
        'location' => '14 Freshwater Drive Cobbity (Mirvac Estate)',
        'building_type' => 'Double Storey',
        'build_up_area' => '32 sqs',
        'cover' => $IMG . '/project-tours/nirvana.jpg',
        'gallery_dir' => $IMG . '/projects/nirvana', 'count' => 15,
        'order' => 2,
    ],
    [
        'slug' => '72-voyager',
        'title' => '72 Voyager st, wadalba NSW',
        'location' => 'wadalba, Central coast, NSW',
        'building_type' => 'Single Storey',
        'build_up_area' => '30 sqs',
        'cover' => $IMG . '/project-tours/voyager-72.jpg',
        'gallery_dir' => $IMG . '/projects/72-voyager', 'count' => 22,
        'order' => 3,
    ],
    [
        'slug' => '33-warman',
        'title' => '33 Warman St Pendlehill',
        'location' => 'Pendle hill',
        'building_type' => 'Double Storey High end Premium House',
        'build_up_area' => '55 sqs',
        'cover' => $IMG . '/project-tours/warman-33.jpg',
        'gallery_dir' => $IMG . '/projects/33-warman', 'count' => 20,
        'order' => 4,
    ],
    [
        'slug' => 'akuna-vista',
        'title' => 'The Gateway at Akuna Vista',
        'location' => '121 Aerodrome Drive, Nirimba Fields, NSW 2763',
        'building_type' => 'Double Storey House with Detached Garage and Studio',
        'build_up_area' => '44 sqs',
        'cover' => $IMG . '/project-tours/akuna-vista.jpg',
        'gallery_dir' => $IMG . '/projects/akuna-vista', 'count' => 21,
        'order' => 5,
    ],
];

foreach ($seed as $p) {
    $cover = '';
    if (is_file($p['cover'])) {
        $cover = upload_copy_existing($p['cover'], 'cover') ?? '';
    }

    $id = project_create([
        'title'         => $p['title'],
        'slug'          => project_unique_slug($p['slug']),
        'location'      => $p['location'],
        'building_type' => $p['building_type'],
        'build_up_area' => $p['build_up_area'],
        'short_description' => '',
        'description'   => '',
        'cover_image'   => $cover,
        'status'        => 'published',
        'is_featured'   => 0,
        'display_order' => $p['order'],
    ]);

    $added = 0;
    for ($i = 1; $i <= $p['count']; $i++) {
        $src = $p['gallery_dir'] . '/img-' . $i . '.jpg';
        if (!is_file($src)) {
            continue;
        }
        $name = upload_copy_existing($src, 'gallery');
        if ($name) {
            project_image_add($id, $name);
            $added++;
        }
    }
    $line(sprintf('Seeded "%s"  (cover: %s, gallery: %d/%d)', $p['title'], $cover ? 'yes' : 'no', $added, $p['count']));
}

$line('Project seed complete.');
