<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Certificate.php';

$db = (new Database())->connect();
$certificateModel = new Certificate($db);

$id = (int)($_GET['id'] ?? 0);
$requestData = $certificateModel->getRequestById($id);

if (!$requestData) {
    set_flash('danger', 'Certificate request not found.');
    redirect('certificates/history.php');
}

$templateFile = __DIR__ . '/templates/' . $certificateModel->resolveTemplateFile($requestData['certificate_type']);
$certificateMeta = [];

/* NEW: expose official seal path for template/layout use */
$officialSealPath = $requestData['officials']['official_seal'] ?? '';

/* this still prepares the template-specific content/data */
include $templateFile;

$pageTitle = 'Certificate Preview';
$pageSubtitle = 'Review the final certificate layout before sending it to the printer.';
$pageIcon = 'bi bi-eye-fill';
$crumb = 'Home / Certificates / Preview';
$pageActions =
    '<a href="' . e(url('certificates/history.php')) . '" class="btn btn-soft me-2">Back to History</a>' .
    '<a href="' . e(url('certificates/print.php?id=' . $requestData['id'])) . '" target="_blank" class="btn btn-gradient">Open Printable Version</a>';
$currentPage = 'certificates';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
include __DIR__ . '/templates/layout.php';
include __DIR__ . '/../includes/footer.php';
?>