<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';

$db = (new Database())->connect();
$residentModel = new Resident($db);

$searchTerm = trim((string) ($_GET['q'] ?? ''));

$residents = $searchTerm !== ''
    ? $residentModel->search($searchTerm)
    : $residentModel->getAll();

$pageTitle = 'Residents';
$pageSubtitle = 'Manage Barangay TRX resident records with search, profile access, and CRUD operations.';
$pageIcon = 'bi bi-people-fill';
$crumb = 'Home / Residents';

$pageActions = '<a href="' . e(url('residents/create.php')) . '" class="btn btn-gradient">Add Resident</a>';

$currentPage = 'residents';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';

?>

<div class="card mb-4">
    <div class="card-body p-4">

        <form method="GET" class="row g-3 align-items-center">

            <div class="col-lg-9">
                <label class="form-label small text-uppercase text-muted">Search Residents</label>
                <input type="text" name="q" class="form-control"
                       placeholder="Resident no, full name, or address"
                       value="<?= e($searchTerm) ?>">
            </div>

            <div class="col-lg-3 d-grid d-lg-flex gap-2 align-self-end">
                <button type="submit" class="btn btn-gradient flex-grow-1">Search</button>
                <a href="<?= e(url('residents/index.php')) ?>" class="btn btn-soft flex-grow-1">Reset</a>
            </div>

        </form>

    </div>
</div>

<div class="card">

    <div class="card-header bg-transparent px-4 py-3 d-flex justify-content-between align-items-center">

        <div>
            <h5 class="mb-1 fw-bold">Resident Masterlist</h5>
            <small class="text-muted">
                <?= e(count($residents)) ?> resident record(s) displayed.
            </small>
        </div>

        <span class="badge-soft">Barangay TRX</span>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-theme align-middle mb-0">

                <thead>
                    <tr>
                        <th>Resident No.</th>
                        <th>Full Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (empty($residents)): ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No resident records found.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($residents as $resident): ?>

                        <tr>

                            <td>
                                <span class="badge-soft">
                                    <?= e($resident['resident_no']) ?>
                                </span>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    <?= e(format_full_name($resident)) ?>
                                </div>
                                <small class="text-muted">
                                    <?= e($resident['civil_status'] ?: 'N/A') ?>
                                </small>
                            </td>

                            <td><?= e($resident['sex']) ?></td>
                            <td><?= e($resident['age']) ?></td>
                            <td><?= e($resident['address']) ?></td>

                            <td>
                                <span class="badge-soft">
                                    <?= e($resident['resident_status']) ?>
                                </span>
                            </td>

                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-2 flex-wrap">

                                    <a href="<?= e(url('residents/view.php?id=' . $resident['id'])) ?>"
                                       class="btn btn-soft btn-sm">
                                        View
                                    </a>

                                    <a href="<?= e(url('residents/edit.php?id=' . $resident['id'])) ?>"
                                       class="btn btn-outline-trx btn-sm">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="<?= e(url('residents/delete.php')) ?>"
                                          class="d-inline js-confirm-delete">

                                        <?= csrf_field() ?>

                                        <input type="hidden" name="id"
                                               value="<?= e($resident['id']) ?>">

                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>

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