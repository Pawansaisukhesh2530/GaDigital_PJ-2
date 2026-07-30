<?php
/** Log out: destroy session + cookie, redirect to login with a notice. */
require __DIR__ . '/init.php';

auth_logout();
redirect(admin_url('index.php') . '?logout=1');
