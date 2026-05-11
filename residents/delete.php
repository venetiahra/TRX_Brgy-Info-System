<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';

if (!is_post()) {
    redirect('residents/index.php');
}

verify_csrf();

$id = (int) ($_POST['id'] ?? 0);

$db = (new Database())->connect();
$residentModel = new Resident($db);

if ($id > 0) {
    $residentModel->delete($id);
    set_flash('success', 'Resident record deleted successfully.');
}

redirect('residents/index.php');

?>