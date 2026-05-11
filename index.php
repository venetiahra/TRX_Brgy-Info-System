<?php

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/User.php';

if (isset($_SESSION['user'])) {
    redirect('dashboard.php');
}

$db = (new Database())->connect();
$u  = new User($db);

if ($u->countUsers() === 0) {
    redirect('auth/register_admin.php');
}

redirect('client/index.php');

?>