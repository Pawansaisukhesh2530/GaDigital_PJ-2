<?php
/**
 * DANGER: wipes all project data and uploaded project images, then lets the
 * seeder repopulate a pristine copy. Dev/setup use only.
 * Run:  php database/reset_projects.php && php database/seed_projects.php
 */
require_once __DIR__ . '/../app/bootstrap.php';

db()->exec('DELETE FROM project_features');
db()->exec('DELETE FROM project_images');
db()->exec('DELETE FROM projects');
// Reset auto-increment counters so ids start clean.
db()->exec("DELETE FROM sqlite_sequence WHERE name IN ('projects','project_images','project_features')");

// Clear uploaded project image files.
$deleted = 0;
foreach (glob(UPLOAD_DIR . '/*') as $f) {
    if (is_file($f) && basename($f) !== '.htaccess') {
        @unlink($f);
        $deleted++;
    }
}
echo "Cleared project tables and $deleted uploaded files." . PHP_EOL;
