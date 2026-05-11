<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Certificate.php';
$db = (new Database())->connect();
$certificateModel = new Certificate($db);
$id = (int) ($_GET['id'] ?? 0);
$requestData = $certificateModel->getRequestById($id);
if (!$requestData) {
    exit('Certificate request not found.');
}
$templateFile = __DIR__ . '/templates/' . $certificateModel->resolveTemplateFile($requestData['certificate_type']);
$certificateMeta = [];
include $templateFile;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($requestData['certificate_type']) ?> - Print</title>
    <link rel="icon" type="image/x-icon" href="<?= e(url('assets/favicon.ico')) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
</head>
<body class="bg-white">
    <main class="container py-4">
        <div class="d-flex justify-content-end gap-2 mb-3 no-print">
            <button onclick="window.print()" class="btn btn-gradient">Print</button>
            <button onclick="window.close()" class="btn btn-outline-trx">Close</button>
        </div>
        <?php include __DIR__ . '/templates/layout.php'; ?>
    </main>
</body>
</html>
