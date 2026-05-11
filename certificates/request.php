<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';
require_once __DIR__ . '/../classes/Certificate.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();

$residentModel = new Resident($db);
$certificateModel = new Certificate($db);

$residents = $residentModel->getDropdownOptions();
$certificateTypes = $certificateModel->getCertificateTypes();

$formData = [
    'resident_id' => (string) ($_GET['resident_id'] ?? ''),
    'certificate_type' => '',
    'purpose' => '',
    'control_no' => '',
    'or_no' => '',
    'date_issued' => date('Y-m-d'),
    'issued_by' => $_SESSION['user']['fullname'] ?? 'Barangay Staff',
    'remarks' => ''
];

$errors = [];

if (is_post()) {

    verify_csrf();

    $formData = array_merge($formData, Validator::sanitizeArray($_POST));

    $errors = Validator::validateRequired(
        $formData,
        [
            'resident_id' => 'Resident',
            'certificate_type' => 'Certificate type',
            'purpose' => 'Purpose',
            'date_issued' => 'Date issued',
            'issued_by' => 'Issued by'
        ]
    );

    if (empty($errors)) {

        $requestId = $certificateModel->createRequest($formData);

        set_flash('success', 'Certificate request saved successfully.');

        redirect('certificates/preview.php?id=' . $requestId);
    }
}

$pageTitle = 'Request Certificate';
$pageSubtitle = 'Create a certificate request with preview and print output.';
$pageIcon = 'bi bi-file-earmark-plus-fill';
$crumb = 'Home / Certificates / Request';

$pageActions = '<a href="' . e(url('certificates/history.php')) . '" class="btn btn-soft">Open History</a>';

$currentPage = 'certificates';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';

?>

<?php if (empty($residents)): ?>

    <div class="alert alert-warning">
        No residents found. Please add a resident record before requesting a certificate.
    </div>

    <a href="<?= e(url('residents/create.php')) ?>" class="btn btn-gradient">
        Add Resident
    </a>

<?php else: ?>

    <div class="card">

        <div class="card-body p-4 p-lg-4">

            <form method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Resident *</label>

                        <select name="resident_id"
                                class="form-select <?= isset($errors['resident_id']) ? 'is-invalid' : '' ?>">

                            <option value="">Select resident</option>

                            <?php foreach ($residents as $resident): ?>
                                <option value="<?= e($resident['id']) ?>"
                                    <?= selected($resident['id'], $formData['resident_id'] ?? '') ?>>
                                    <?= e($resident['resident_no'] . ' - ' . format_full_name($resident)) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                        <div class="invalid-feedback">
                            <?= e($errors['resident_id'] ?? '') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Certificate Type *</label>

                        <select name="certificate_type"
                                class="form-select <?= isset($errors['certificate_type']) ? 'is-invalid' : '' ?>">

                            <option value="">Select certificate</option>

                            <?php foreach ($certificateTypes as $type): ?>
                                <option value="<?= e($type) ?>"
                                    <?= selected($type, $formData['certificate_type'] ?? '') ?>>
                                    <?= e($type) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>

                        <div class="invalid-feedback">
                            <?= e($errors['certificate_type'] ?? '') ?>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Purpose *</label>

                        <input type="text"
                               name="purpose"
                               class="form-control <?= isset($errors['purpose']) ? 'is-invalid' : '' ?>"
                               value="<?= e($formData['purpose'] ?? '') ?>"
                               placeholder="Employment, school requirement, scholarship, legal purpose">

                        <div class="invalid-feedback">
                            <?= e($errors['purpose'] ?? '') ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Control Number</label>

                        <input type="text"
                               name="control_no"
                               class="form-control"
                               value="<?= e($formData['control_no'] ?? '') ?>"
                               placeholder="Auto-generated if blank">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">OR Number</label>

                        <input type="text"
                               name="or_no"
                               class="form-control"
                               value="<?= e($formData['or_no'] ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date Issued *</label>

                        <input type="date"
                               name="date_issued"
                               class="form-control <?= isset($errors['date_issued']) ? 'is-invalid' : '' ?>"
                               value="<?= e($formData['date_issued'] ?? '') ?>">

                        <div class="invalid-feedback">
                            <?= e($errors['date_issued'] ?? '') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Issued By *</label>

                        <input type="text"
                               name="issued_by"
                               class="form-control <?= isset($errors['issued_by']) ? 'is-invalid' : '' ?>"
                               value="<?= e($formData['issued_by'] ?? '') ?>">

                        <div class="invalid-feedback">
                            <?= e($errors['issued_by'] ?? '') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Remarks</label>

                        <input type="text"
                               name="remarks"
                               class="form-control"
                               value="<?= e($formData['remarks'] ?? '') ?>">
                    </div>

                </div>

                <div class="d-flex gap-2 mt-4 flex-wrap no-print">
                    <button type="submit" class="btn btn-gradient">
                        Save & Preview Certificate
                    </button>

                    <a href="<?= e(url('certificates/history.php')) ?>" class="btn btn-soft">
                        Open History
                    </a>
                </div>

            </form>

        </div>

    </div>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>