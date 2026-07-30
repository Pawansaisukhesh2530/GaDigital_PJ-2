<?php
/** Admin - Delete Project (POST + CSRF). Removes child rows + image files. */
require __DIR__ . '/../init.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);
$project = project_find($id);
if ($project) {
    project_delete($id);   // deletes gallery + feature rows (cascade) and image files
    flash_set('flash_success', 'Project "' . $project['title'] . '" was deleted.');
} else {
    flash_set('flash_error', 'Project not found.');
}
redirect('index.php');
