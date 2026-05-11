<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Certificate.php';

$db = (new Database())->connect();
$certificateModel = new Certificate($db);

$searchTerm = trim((string) ($_GET['q'] ?? ''));

$requests = $certificateModel->getAllRequests($searchTerm);

$pageTitle = 'Certificate History';
$pageSubtitle = 'Audit certificate issuance, search requests, and reopen printable records anytime.';
$pageIcon = 'bi bi-clock-history';
$crumb = 'Home / Certificates / History';

$pageActions = '<a href="' . e(url('certificates/request.php')) . '" class="btn btn-gradient">New Request</a>';

$currentPage = 'history';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';

?>

<div class="card mb-4">
    <div class="card-body p-4">

        <form method="GET" class="row g-3 align-items-center">

            <div class="col-lg-9">
                <label class="form-label small text-uppercase text-muted">Search History</label>
                <input type="text" name="q" class="form-control"
                       placeholder="Resident, type, purpose, issued by, or control no."
                       value="<?= e($searchTerm) ?>">
            </div>

            <div class="col-lg-3 d-grid d-lg-flex gap-2 align-self-end">
                <button type="submit" class="btn btn-gradient flex-grow-1">Search</button>
                <a href="<?= e(url('certificates/history.php')) ?>" class="btn btn-soft flex-grow-1">Reset</a>
            </div>

        </form>

    </div>
</div>

<div class="card">

    <div class="card-header bg-transparent px-4 py-3 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-1 fw-bold">Issued Certificates</h5>
            <small class="text-muted">
                <?= e(count($requests)) ?> request record(s) displayed.
            </small>
        </div>

        <span class="badge-soft">History Log</span>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-theme align-middle mb-0">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Resident</th>
                        <th>Certificate Type</th>
                        <th>Purpose</th>
                        <th>Date Issued</th>
                        <th>Issued By</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($requests)): ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No certificate requests found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($requests as $request): ?>

                        <tr>

                            <td>#<?= e($request['id']) ?></td>
                            <td><?= e($request['resident_full_name']) ?></td>
                            <td><?= e($request['certificate_type']) ?></td>
                            <td><?= e($request['purpose']) ?></td>
                            <td><?= e(format_date_human($request['date_issued'])) ?></td>
                            <td><?= e($request['issued_by']) ?></td>

                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2 flex-wrap">

                                    <a href="<?= e(url('certificates/preview.php?id=' . $request['id'])) ?>"
                                       class="btn btn-soft btn-sm">
                                        Preview
                                    </a>

                                    <a href="<?= e(url('certificates/print.php?id=' . $request['id'])) ?>"
                                       target="_blank"
                                       class="btn btn-outline-trx btn-sm">
                                        Print
                                    </a>

                                </div>

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