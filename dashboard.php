<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/classes/Resident.php';
require_once __DIR__ . '/classes/Certificate.php';
require_once __DIR__ . '/classes/Blotter.php';

$db = (new Database())->connect();
$residentModel = new Resident($db);
$certificateModel = new Certificate($db);
$blotterModel = new Blotter($db);

$totalResidents = $residentModel->count();
$totalRequests = $certificateModel->countRequests();
$totalBlotters = $blotterModel->count();
$supportedCertificates = count($certificateModel->getCertificateTypes());
$recentRequests = array_slice($certificateModel->getAllRequests(), 0, 5);

$statusRows = $db->query("SELECT resident_status, COUNT(*) total FROM residents GROUP BY resident_status ORDER BY total DESC")->fetchAll();
$typeRows = $db->query("SELECT certificate_type, COUNT(*) total FROM certificate_requests GROUP BY certificate_type ORDER BY total DESC LIMIT 6")->fetchAll();

$statusLabels = [];
$statusData = [];
foreach ($statusRows as $row) {
    $statusLabels[] = $row['resident_status'] ?: 'Unknown';
    $statusData[] = (int) $row['total'];
}

$typeLabels = [];
$typeData = [];
foreach ($typeRows as $row) {
    $typeLabels[] = $row['certificate_type'];
    $typeData[] = (int) $row['total'];
}

$pageTitle = 'Dashboard';
$pageSubtitle = 'Dashtreme-style admin dashboard with separate client portal design.';
$pageIcon = 'bi bi-grid-1x2-fill';
$crumb = 'Home / Dashboard';
$pageActions =
    '<a href="' . e(url('residents/create.php')) . '" class="btn btn-gradient me-2">Add Resident</a>' .
    '<a href="' . e(url('client/index.php')) . '" class="btn btn-soft">Open Client Portal</a>';
$currentPage = 'dashboard';

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/page_header.php';
?>

<div class="admin-city-overlay rounded-4 p-2">
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 gradient-green">
                <div class="card-body p-4">
                    <div class="metric-label">Total Residents</div>
                    <div class="metric-value"><?= e($totalResidents) ?></div>
                    <div class="metric-note mt-2">Resident records ready.</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 gradient-blue">
                <div class="card-body p-4">
                    <div class="metric-label">Certificate Requests</div>
                    <div class="metric-value"><?= e($totalRequests) ?></div>
                    <div class="metric-note mt-2">Preview and print supported.</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 gradient-purple">
                <div class="card-body p-4">
                    <div class="metric-label">Certificate Types</div>
                    <div class="metric-value"><?= e($supportedCertificates) ?></div>
                    <div class="metric-note mt-2">Full dropdown list included.</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card metric-card h-100 gradient-pink">
                <div class="card-body p-4">
                    <div class="metric-label">Blotter Reports</div>
                    <div class="metric-value"><?= e($totalBlotters) ?></div>
                    <div class="metric-note mt-2">Admin blotter workflow enabled.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-body p-4">
                    <div class="chart-header mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Resident Status Distribution</h5>
                            <small class="text-muted">Live count by resident status.</small>
                        </div>
                        <span class="mini-badge">Chart.js</span>
                    </div>
                    <canvas id="residentStatusChart" height="220"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card chart-card h-100">
                <div class="card-body p-4">
                    <div class="chart-header mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Top Certificate Types</h5>
                            <small class="text-muted">Most requested certificates.</small>
                        </div>
                        <span class="mini-badge">Real Data</span>
                    </div>
                    <canvas id="certificateTypeChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="quick-grid mb-4">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2">Blotter Module</h5>
                <p class="text-muted mb-3">Manage complaint records, schedules, and case updates.</p>
                <a href="<?= e(url('blotter/index.php')) ?>" class="btn btn-gradient btn-sm">Open Blotter</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2">Officials Portfolio</h5>
                <p class="text-muted mb-3">Edit barangay officials, biographies, and upload official images.</p>
                <a href="<?= e(url('officials/index.php')) ?>" class="btn btn-soft btn-sm">Manage Officials</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2">Client Portal</h5>
                <p class="text-muted mb-3">Public users can request certificates and file blotter reports online.</p>
                <a href="<?= e(url('client/index.php')) ?>" class="btn btn-soft btn-sm">Open Client Side</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-transparent px-4 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 fw-bold">Recent Certificate Activity</h5>
                <small class="text-muted">Latest requests saved in the system.</small>
            </div>
            <a href="<?= e(url('certificates/history.php')) ?>" class="btn btn-soft btn-sm">Full History</a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-theme mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Resident</th>
                            <th>Certificate Type</th>
                            <th>Date Issued</th>
                            <th>Issued By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentRequests)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">No certificate requests yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentRequests as $request): ?>
                                <tr>
                                    <td>#<?= e($request['id']) ?></td>
                                    <td><?= e($request['resident_full_name']) ?></td>
                                    <td><?= e($request['certificate_type']) ?></td>
                                    <td><?= e(format_date_human($request['date_issued'])) ?></td>
                                    <td><?= e($request['issued_by']) ?></td>
                                    <td>
                                        <a href="<?= e(url('certificates/preview.php?id=' . $request['id'])) ?>" class="btn btn-soft btn-sm">Preview</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const residentStatusCtx = document.getElementById('residentStatusChart');
if (residentStatusCtx) {
    new Chart(residentStatusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($statusLabels) ?>,
            datasets: [{
                data: <?= json_encode($statusData) ?>,
                backgroundColor: ['#1FA463', '#1573D6', '#7F7FD5', '#FF758C', '#F5B700', '#66D08F']
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: { color: '#eef2ff' }
                }
            }
        }
    });
}

const certificateTypeCtx = document.getElementById('certificateTypeChart');
if (certificateTypeCtx) {
    new Chart(certificateTypeCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($typeLabels) ?>,
            datasets: [{
                label: 'Requests',
                data: <?= json_encode($typeData) ?>,
                backgroundColor: '#1573D6'
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: { color: '#eef2ff' }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#eef2ff' },
                    grid: { color: 'rgba(255,255,255,0.06)' }
                },
                y: {
                    beginAtZero: true,
                    ticks: { color: '#eef2ff' },
                    grid: { color: 'rgba(255,255,255,0.06)' }
                }
            }
        }
    });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>