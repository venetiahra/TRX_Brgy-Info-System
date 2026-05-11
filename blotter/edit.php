<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Blotter.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$blotterModel = new Blotter($db);

$id = (int) ($_GET['id'] ?? 0);
$record = $blotterModel->get($id);

if (!$record) {
    set_flash('danger', 'Blotter record not found.');
    redirect('blotter/index.php');
}

$formData = $record;
$errors = [];

if (is_post()) {
    verify_csrf();
    $formData = array_merge($formData, Validator::sanitizeArray($_POST));

    $errors = Validator::validateRequired($formData, [
        'complainant_name' => 'Complainant',
        'respondent_name' => 'Respondent',
        'incident_date' => 'Incident date',
        'incident_location' => 'Incident location',
        'complaint_details' => 'Complaint details'
    ]);

    if (empty($errors)) {
        $blotterModel->update($id, $formData);
        set_flash('success', 'Blotter record updated successfully.');
        redirect('blotter/view.php?id=' . $id);
    }
}

$pageTitle = 'Edit Blotter';
$pageSubtitle = 'Update blotter status, schedule, and case details.';
$pageIcon = 'bi bi-pencil-square';
$crumb = 'Home / Blotter / Edit';
$currentPage = 'blotter';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <div class="fw-bold mb-2">Please fix the following:</div>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body p-4">
        <form method="POST">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Complainant Name</label>
                    <input type="text" name="complainant_name" class="form-control" value="<?= e($formData['complainant_name']) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Respondent Name</label>
                    <input type="text" name="respondent_name" class="form-control" value="<?= e($formData['respondent_name']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="<?= e($formData['contact_number']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Incident Date</label>
                    <input type="date" name="incident_date" class="form-control" value="<?= e($formData['incident_date']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Incident Time</label>
                    <input type="time" name="incident_time" class="form-control" value="<?= e($formData['incident_time']) ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Incident Location</label>
                    <input type="text" name="incident_location" class="form-control" value="<?= e($formData['incident_location']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['Pending Review', 'For Mediation', 'Scheduled', 'Settled', 'Archived'] as $status): ?>
                            <option value="<?= e($status) ?>" <?= selected($status, $formData['status']) ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Complaint Details</label>
                    <textarea name="complaint_details" rows="5" class="form-control"><?= e($formData['complaint_details']) ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Schedule Date</label>
                    <input
                        type="datetime-local"
                        name="schedule_date"
                        class="form-control"
                        value="<?= e(!empty($formData['schedule_date']) ? date('Y-m-d\TH:i', strtotime($formData['schedule_date'])) : '') ?>"
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">Remarks</label>
                    <input type="text" name="remarks" class="form-control" value="<?= e($formData['remarks']) ?>">
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-gradient">Update Blotter</button>
                <a href="<?= e(url('blotter/view.php?id=' . $id)) ?>" class="btn btn-soft">Back</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
``