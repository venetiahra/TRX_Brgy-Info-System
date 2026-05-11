<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Blotter.php';

$db = (new Database())->connect();
$blotterModel = new Blotter($db);

/* NEW: quick status update */
if (is_post() && isset($_POST['record_id'], $_POST['set_status'])) {
    verify_csrf();

    $recordId = (int)$_POST['record_id'];
    $newStatus = trim((string)$_POST['set_status']);

    if ($blotterModel->updateStatus($recordId, $newStatus)) {
        set_flash('success', 'Blotter status updated to ' . $newStatus . '.');
    } else {
        set_flash('danger', 'Unable to update blotter status.');
    }

    redirect('blotter/index.php');
}

$searchTerm = trim((string)($_GET['q'] ?? ''));
$records = $blotterModel->all($searchTerm);

$pageTitle = 'Blotter Records';
$pageSubtitle = 'Admin-side blotter management and status updates.';
$pageIcon = 'bi bi-journal-text';
$crumb = 'Home / Blotter';
$pageActions = '<a href="' . e(url('blotter/create.php')) . '" class="btn btn-gradient">Add Blotter Entry</a>';
$currentPage = 'blotter';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
?>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-lg-9">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Search control no, complainant, respondent, location, or status"
                    value="<?= e($searchTerm) ?>"
                >
            </div>
            <div class="col-lg-3 d-grid d-lg-flex gap-2">
                <button class="btn btn-gradient flex-grow-1">Search</button>
                <a href="<?= e(url('blotter/index.php')) ?>" class="btn btn-soft flex-grow-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-theme mb-0">
                <thead>
                    <tr>
                        <th>Control No.</th>
                        <th>Complainant</th>
                        <th>Respondent</th>
                        <th>Incident Date</th>
                        <th>Status</th>
                        <th>Quick Status</th>
                        <th>Submitted Via</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">No blotter records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                            <?php
                            $statusValue = trim((string)($record['status'] ?? 'Pending Review'));
                            $isComplete = strcasecmp($statusValue, 'Complete') === 0;
                            ?>
                            <tr>
                                <td><?= e($record['control_no']) ?></td>
                                <td><?= e($record['complainant_name']) ?></td>
                                <td><?= e($record['respondent_name']) ?></td>
                                <td><?= e(format_date_human($record['incident_date'])) ?></td>

                                <td>
                                    <?php if ($isComplete): ?>
                                        <span class="badge-soft" style="background:rgba(31,164,99,.18); border-color:rgba(31,164,99,.35); color:#dff9e8;">
                                            Complete
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-soft" style="background:rgba(255,179,107,.18); border-color:rgba(255,179,107,.35); color:#ffe2c2;">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <form method="POST" class="d-flex gap-2">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="record_id" value="<?= e($record['id']) ?>">

                                        <button
                                            type="submit"
                                            name="set_status"
                                            value="Pending"
                                            class="btn btn-sm <?= !$isComplete ? 'btn-gradient' : 'btn-soft' ?>"
                                        >
                                            Pending
                                        </button>

                                        <button
                                            type="submit"
                                            name="set_status"
                                            value="Complete"
                                            class="btn btn-sm <?= $isComplete ? 'btn-gradient' : 'btn-soft' ?>"
                                        >
                                            Complete
                                        </button>
                                    </form>
                                </td>

                                <td><?= e($record['submitted_via']) ?></td>

                                <td class="text-end">
                                    <a href="<?= e(url('blotter/view.php?id=' . $record['id'])) ?>" class="btn btn-soft btn-sm">View</a>
                                    <a href="<?= e(url('blotter/edit.php?id=' . $record['id'])) ?>" class="btn btn-outline-trx btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>