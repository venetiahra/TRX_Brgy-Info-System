<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';
require_once __DIR__ . '/../classes/Certificate.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$residents = (new Resident($db))->getDropdownOptions();
$cert = new Certificate($db);
$types = $cert->getCertificateTypes();

$form = [
    'resident_id' => '',
    'certificate_type' => '',
    'purpose' => '',
    'control_no' => '',
    'or_no' => '',
    'date_issued' => date('Y-m-d'),
    'issued_by' => 'Client Portal',
    'remarks' => 'Submitted via client portal'
];

$errors = [];

if (is_post()) {
    verify_csrf();
    $form = array_merge($form, Validator::sanitizeArray($_POST));

    $errors = Validator::validateRequired($form, [
        'resident_id' => 'Resident',
        'certificate_type' => 'Certificate type',
        'purpose' => 'Purpose'
    ]);

    if (empty($errors)) {
        $id = $cert->createRequest($form);
        $record = $cert->getRequestById($id);

        set_flash(
            'success',
            'Certificate request submitted. Your control number is ' . ($record['control_no'] ?? 'N/A') . '.'
        );

        redirect('client/history.php?q=' . urlencode($record['control_no'] ?? ''));
    }
}

$pageTitle = 'Request Certificate';
$activePage = 'request';
include __DIR__ . '/includes/header.php';
?>

<style>
.page-shell {
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(31,164,99,.10), transparent 26%),
        radial-gradient(circle at bottom left, rgba(21,115,214,.12), transparent 26%),
        linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
    border: 1px solid #dce6f5;
    box-shadow: 0 18px 40px rgba(16,36,84,.08);
}
.section-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem .9rem;
    border-radius: 999px;
    background: rgba(21,115,214,.08);
    border: 1px solid rgba(21,115,214,.12);
    color: #0B2E83;
    font-weight: 700;
    font-size: .85rem;
}
.page-title-premium {
    color: #0B2E83;
    font-weight: 800;
}
.page-lead {
    color: #6f7b9f;
}
.info-strip {
    border-radius: 20px;
    background: linear-gradient(135deg, #0B2E83 0%, #1573D6 100%);
    color: #fff;
}
.form-card-premium {
    border-radius: 24px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 14px 30px rgba(16,36,84,.06);
}
.quick-tip {
    border-radius: 20px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 10px 24px rgba(16,36,84,.05);
}
.icon-pill {
    width: 54px;
    height: 54px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    color: #fff;
    font-size: 1.2rem;
    background: linear-gradient(135deg, #1FA463, #1573D6);
}
</style>

<div class="page-shell p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="section-badge mb-3">
                <i class="bi bi-file-earmark-plus-fill"></i>
                Client Certificate Service
            </div>
            <h1 class="display-6 page-title-premium mb-2">Request a barangay certificate</h1>
            <p class="page-lead mb-0">
                Submit your request online and receive a control number for tracking.
            </p>
        </div>
        <div class="col-lg-4">
            <div class="info-strip p-4 h-100">
                <div class="fw-bold mb-2">What you need</div>
                <ul class="mb-0 ps-3 small">
                    <li>Select the resident record</li>
                    <li>Choose the certificate type</li>
                    <li>Enter a clear purpose</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger shadow-sm">
        <div class="fw-bold mb-2">Please fix the following:</div>
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-card-premium p-4 p-lg-5">
            <h3 class="fw-bold mb-4">Certificate Request Form</h3>

            <form method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Resident</label>
                        <select name="resident_id" class="form-select">
                            <option value="">Select resident</option>
                            <?php foreach ($residents as $resident): ?>
                                <option value="<?= e($resident['id']) ?>" <?= selected($resident['id'], $form['resident_id']) ?>>
                                    <?= e($resident['resident_no'] . ' - ' . format_full_name($resident)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Certificate Type</label>
                        <select name="certificate_type" class="form-select">
                            <option value="">Select certificate</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= e($type) ?>" <?= selected($type, $form['certificate_type']) ?>>
                                    <?= e($type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Purpose</label>
                        <input type="text" name="purpose" class="form-control" value="<?= e($form['purpose']) ?>" placeholder="Example: Scholarship requirement">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">OR Number</label>
                        <input type="text" name="or_no" class="form-control" value="<?= e($form['or_no']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date Submitted</label>
                        <input type="date" name="date_issued" class="form-control" value="<?= e($form['date_issued']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="<?= e($form['remarks']) ?>">
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-client-gradient btn-lg">
                        <i class="bi bi-send-fill me-2"></i>Submit Request
                    </button>
                    <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline btn-lg">
                        <i class="bi bi-search me-2"></i>Track Existing Request
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="quick-tip p-4 mb-4">
            <div class="icon-pill mb-3">
                <i class="bi bi-info-circle-fill"></i>
            </div>
            <h5 class="fw-bold mb-2">Helpful reminder</h5>
            <p class="text-muted-client mb-0">
                After submission, keep your generated control number so you can check your request status anytime.
            </p>
        </div>

        <div class="quick-tip p-4">
            <h6 class="fw-bold mb-3">Popular actions</h6>
            <div class="d-grid gap-2">
                <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-clock-history me-2"></i>Track Request
                </a>
                <a href="<?= e(url('client/blotter.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-journal-text me-2"></i>Submit Blotter
                </a>
                <a href="<?= e(url('client/officials.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-people-fill me-2"></i>View Officials
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>