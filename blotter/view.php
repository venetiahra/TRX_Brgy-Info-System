<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Blotter.php';

$db = (new Database())->connect();
$blotterModel = new Blotter($db);

$id = (int) ($_GET['id'] ?? 0);
$record = $blotterModel->get($id);

if (!$record) {
    set_flash('danger', 'Blotter record not found.');
    redirect('blotter/index.php');
}

$pageTitle = 'Blotter Details';
$pageSubtitle = 'Review the selected blotter incident and workflow status.';
$pageIcon = 'bi bi-file-earmark-text-fill';
$crumb = 'Home / Blotter / Details';
$pageActions = '<a href="' . e(url('blotter/edit.php?id=' . $record['id'])) . '" class="btn btn-gradient">Edit Record</a>';
$currentPage = 'blotter';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
?>

<div class="card">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-lg-6">
                <h5 class="fw-bold mb-3">Case Information</h5>
                <p><strong>Control No.:</strong> <?= e($record['control_no']) ?></p>
                <p><strong>Status:</strong> <?= e($record['status']) ?></p>
                <p><strong>Complainant:</strong> <?= e($record['complainant_name']) ?></p>
                <p><strong>Respondent:</strong> <?= e($record['respondent_name']) ?></p>
                <p><strong>Submitted Via:</strong> <?= e($record['submitted_via']) ?></p>
                <p><strong>Contact Number:</strong> <?= e($record['contact_number'] ?: 'N/A') ?></p>
            </div>

            <div class="col-lg-6">
                <h5 class="fw-bold mb-3">Incident Details</h5>
                <p><strong>Date:</strong> <?= e(format_date_human($record['incident_date'])) ?></p>
                <p><strong>Time:</strong> <?= e($record['incident_time'] ?: 'N/A') ?></p>
                <p><strong>Location:</strong> <?= e($record['incident_location']) ?></p>
                <p><strong>Schedule:</strong> <?= e(format_datetime_human($record['schedule_date'])) ?></p>
                <p><strong>Created:</strong> <?= e(format_datetime_human($record['created_at'])) ?></p>
                <p><strong>Updated:</strong> <?= e(format_datetime_human($record['updated_at'])) ?></p>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="fw-bold mb-3">Complaint Details</h5>
        <p class="mb-4"><?= nl2br(e($record['complaint_details'])) ?></p>

        <h6 class="fw-bold">Remarks</h6>
        <p class="mb-0"><?= e($record['remarks'] ?: 'N/A') ?></p>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>