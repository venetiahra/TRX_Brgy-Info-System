<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Blotter.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$m = new Blotter($db);

$form = [
    'complainant_name' => '',
    'respondent_name' => '',
    'contact_number' => '',
    'incident_date' => date('Y-m-d'),
    'incident_time' => '',
    'incident_location' => '',
    'complaint_details' => ''
];

$tracked = null;
$trackCode = trim((string) ($_GET['track'] ?? ''));

if ($trackCode !== '') {
    $tracked = $m->byControl($trackCode);
}

if (is_post()) {
    verify_csrf();
    $form = array_merge($form, Validator::sanitizeArray($_POST));

    $id = $m->create(array_merge($form, [
        'submitted_via' => 'Client Portal',
        'status' => 'Pending Review'
    ]));

    $record = $m->get($id);

    set_flash(
        'success',
        'Blotter report submitted. Your control number is ' . ($record['control_no'] ?? 'N/A') . '.'
    );

    redirect('client/blotter.php?track=' . urlencode($record['control_no'] ?? ''));
}

$pageTitle = 'Client Blotter';
$activePage = 'blotter';
include __DIR__ . '/includes/header.php';
?>

<style>
.blotter-hero {
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(255,117,140,.10), transparent 25%),
        radial-gradient(circle at bottom left, rgba(21,115,214,.12), transparent 25%),
        linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
    border: 1px solid #dce6f5;
    box-shadow: 0 18px 40px rgba(16,36,84,.08);
}
.blotter-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem .9rem;
    border-radius: 999px;
    background: rgba(255,117,140,.08);
    border: 1px solid rgba(255,117,140,.14);
    color: #0B2E83;
    font-weight: 700;
    font-size: .85rem;
}
.premium-card {
    border-radius: 24px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 14px 30px rgba(16,36,84,.06);
}
.info-card-premium {
    border-radius: 24px;
    background: linear-gradient(135deg, #0B2E83 0%, #1573D6 100%);
    color: #fff;
}
.track-box {
    border-radius: 20px;
    background: #f9fbff;
    border: 1px solid #dce6f5;
}
</style>

<div class="blotter-hero p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="blotter-badge mb-3">
                <i class="bi bi-journal-text"></i>
                Client Blotter Service
            </div>
            <h1 class="display-6 fw-bold mb-2" style="color:#0B2E83;">Submit and track a blotter report</h1>
            <p class="text-muted-client mb-0">
                File a complaint online and use your generated control number to follow up later.
            </p>
        </div>
        <div class="col-lg-4">
            <div class="info-card-premium p-4 h-100">
                <div class="fw-bold mb-2">After submission</div>
                <ul class="mb-0 ps-3 small">
                    <li>Your report gets a control number</li>
                    <li>Barangay staff reviews the report</li>
                    <li>Status and schedule can be tracked online</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="premium-card p-4 p-lg-5">
            <h3 class="fw-bold mb-4">Blotter Report Form</h3>

            <form method="POST">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Complainant Name</label>
                        <input type="text" name="complainant_name" class="form-control" value="<?= e($form['complainant_name']) ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Respondent Name</label>
                        <input type="text" name="respondent_name" class="form-control" value="<?= e($form['respondent_name']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="<?= e($form['contact_number']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Incident Date</label>
                        <input type="date" name="incident_date" class="form-control" value="<?= e($form['incident_date']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Incident Time</label>
                        <input type="time" name="incident_time" class="form-control" value="<?= e($form['incident_time']) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Incident Location</label>
                        <input type="text" name="incident_location" class="form-control" value="<?= e($form['incident_location']) ?>">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Complaint Details</label>
                        <textarea name="complaint_details" rows="6" class="form-control"><?= e($form['complaint_details']) ?></textarea>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-client-gradient btn-lg">
                        <i class="bi bi-send-fill me-2"></i>Submit Blotter
                    </button>
                    <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline btn-lg">
                        <i class="bi bi-search me-2"></i>Track Certificate
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="premium-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Track Blotter Report</h5>

            <form method="GET" class="d-flex gap-2 mb-3">
                <input
                    type="text"
                    name="track"
                    class="form-control"
                    placeholder="Enter control number"
                    value="<?= e($trackCode) ?>"
                >
                <button class="btn btn-client-gradient">Track</button>
            </form>

            <?php if ($trackCode !== ''): ?>
                <?php if ($tracked): ?>
                    <div class="track-box p-3">
                        <p class="mb-1"><strong>Control No.:</strong> <?= e($tracked['control_no']) ?></p>
                        <p class="mb-1"><strong>Status:</strong> <?= e($tracked['status']) ?></p>
                        <p class="mb-1"><strong>Incident Date:</strong> <?= e(format_date_human($tracked['incident_date'])) ?></p>
                        <p class="mb-1"><strong>Location:</strong> <?= e($tracked['incident_location']) ?></p>
                        <p class="mb-0"><strong>Schedule:</strong> <?= e(format_datetime_human($tracked['schedule_date'])) ?></p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">No blotter report found for that control number.</div>
                <?php endif; ?>
            <?php else: ?>
                <p class="text-muted-client mb-0">
                    Enter the control number you received after submission to check your blotter status.
                </p>
            <?php endif; ?>
        </div>

        <div class="premium-card p-4">
            <h6 class="fw-bold mb-3">Quick links</h6>
            <div class="d-grid gap-2">
                <a href="<?= e(url('client/request.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Request Certificate
                </a>
                <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-clock-history me-2"></i>Track Certificate
                </a>
                <a href="<?= e(url('client/officials.php')) ?>" class="btn btn-client-outline">
                    <i class="bi bi-people-fill me-2"></i>View Officials
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>