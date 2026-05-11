<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';

$db = (new Database())->connect();
$residentModel = new Resident($db);

$id = (int) ($_GET['id'] ?? 0);

$resident = $residentModel->getById($id);

if (!$resident) {
    set_flash('danger', 'Resident record not found.');
    redirect('residents/index.php');
}

$pageTitle = 'Resident Profile';
$pageSubtitle = 'Open the complete resident profile and request certificates directly from the profile page.';
$pageIcon = 'bi bi-person-vcard-fill';
$crumb = 'Home / Residents / Profile';

$pageActions =
    '<a href="' . e(url('certificates/request.php?resident_id=' . $resident['id'])) . '" class="btn btn-gradient me-2">Request Certificate</a>'
  . '<a href="' . e(url('residents/edit.php?id=' . $resident['id'])) . '" class="btn btn-soft me-2">Edit Profile</a>'
  . '<a href="' . e(url('residents/index.php')) . '" class="btn btn-outline-trx">Back to List</a>';

$currentPage = 'residents';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';

?>

<div class="row g-4">

    <div class="col-lg-4">

        <div class="card h-100">

            <div class="card-body p-4 text-center">

                <div class="seal-box mx-auto mb-3">Resident</div>

                <h3 class="fw-bold mb-1">
                    <?= e(format_full_name($resident)) ?>
                </h3>

                <div class="badge-soft mb-3">
                    <?= e($resident['resident_no']) ?>
                </div>

                <p class="text-muted mb-0">
                    <?= e($resident['address']) ?>
                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card h-100">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">Resident Details</h5>

                <dl class="row profile-list mb-0">

                    <dt class="col-sm-4">Sex</dt>
                    <dd class="col-sm-8"><?= e($resident['sex']) ?></dd>

                    <dt class="col-sm-4">Civil Status</dt>
                    <dd class="col-sm-8"><?= e($resident['civil_status'] ?: 'N/A') ?></dd>

                    <dt class="col-sm-4">Birth Date</dt>
                    <dd class="col-sm-8"><?= e(format_date_human($resident['birth_date'])) ?></dd>

                    <dt class="col-sm-4">Age</dt>
                    <dd class="col-sm-8"><?= e($resident['age']) ?></dd>

                    <dt class="col-sm-4">Contact Number</dt>
                    <dd class="col-sm-8"><?= e($resident['contact_number'] ?: 'N/A') ?></dd>

                    <dt class="col-sm-4">Occupation</dt>
                    <dd class="col-sm-8"><?= e($resident['occupation'] ?: 'N/A') ?></dd>

                    <dt class="col-sm-4">Citizenship</dt>
                    <dd class="col-sm-8"><?= e($resident['citizenship'] ?: 'N/A') ?></dd>

                    <dt class="col-sm-4">Years of Residency</dt>
                    <dd class="col-sm-8"><?= e($resident['years_of_residency']) ?></dd>

                    <dt class="col-sm-4">Voter Status</dt>
                    <dd class="col-sm-8"><?= e($resident['voter_status'] ?: 'N/A') ?></dd>

                    <dt class="col-sm-4">Resident Status</dt>
                    <dd class="col-sm-8"><?= e($resident['resident_status']) ?></dd>

                    <dt class="col-sm-4">Created At</dt>
                    <dd class="col-sm-8"><?= e(format_date_human($resident['created_at'])) ?></dd>

                </dl>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>