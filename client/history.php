<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Certificate.php';

$db = (new Database())->connect();
$m = new Certificate($db);

$q = trim((string) ($_GET['q'] ?? ''));
$rows = $m->getAllRequests($q);

$pageTitle = 'Track Request';
$activePage = 'history';
include __DIR__ . '/includes/header.php';
?>

<style>
.tracker-hero {
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(21,115,214,.12), transparent 25%),
        radial-gradient(circle at bottom left, rgba(31,164,99,.12), transparent 25%),
        linear-gradient(135deg, #ffffff 0%, #f6faff 100%);
    border: 1px solid #dce6f5;
    box-shadow: 0 18px 40px rgba(16,36,84,.08);
}
.tracker-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .55rem .9rem;
    border-radius: 999px;
    background: rgba(31,164,99,.08);
    border: 1px solid rgba(31,164,99,.14);
    color: #0B2E83;
    font-weight: 700;
    font-size: .85rem;
}
.tracker-card {
    border-radius: 24px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 14px 30px rgba(16,36,84,.06);
}
.search-pill {
    border-radius: 20px;
    background: rgba(255,255,255,.78);
    border: 1px solid #dce6f5;
    box-shadow: 0 10px 24px rgba(16,36,84,.05);
}
.empty-box {
    border: 1px dashed #cfdcf0;
    border-radius: 20px;
    background: #f9fbff;
}
</style>

<div class="tracker-hero p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-8">
            <div class="tracker-badge mb-3">
                <i class="bi bi-search"></i>
                Request Tracking
            </div>
            <h1 class="display-6 fw-bold mb-2" style="color:#0B2E83;">Track your certificate request</h1>
            <p class="text-muted-client mb-0">
                Search by control number, resident name, certificate type, purpose, or issued by.
            </p>
        </div>
        <div class="col-lg-4">
            <div class="search-pill p-4">
                <div class="fw-bold mb-1" style="color:#17315f;">Quick tip</div>
                <p class="text-muted-client small mb-0">
                    If you submitted your request online, your control number is shown after submission.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="tracker-card p-4 p-lg-5">
    <h3 class="fw-bold mb-3">Search Request Records</h3>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-lg-9">
            <input
                type="text"
                name="q"
                class="form-control"
                placeholder="Enter control no., resident name, certificate type, purpose, or issued by"
                value="<?= e($q) ?>"
            >
        </div>
        <div class="col-lg-3 d-grid d-lg-flex gap-2">
            <button class="btn btn-client-gradient flex-grow-1">
                <i class="bi bi-search me-2"></i>Search
            </button>
            <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline flex-grow-1">
                Reset
            </a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Control No.</th>
                    <th>Resident</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Issued By</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-box text-center py-5">
                                <h5 class="fw-bold mb-2">No matching requests found</h5>
                                <p class="text-muted-client mb-0">Try a different control number, name, or keyword.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($row['control_no']) ?></td>
                            <td><?= e($row['resident_full_name']) ?></td>
                            <td><?= e($row['certificate_type']) ?></td>
                            <td><?= e(format_date_human($row['date_issued'])) ?></td>
                            <td><?= e($row['issued_by']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
