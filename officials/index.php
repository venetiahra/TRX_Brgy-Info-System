<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$row = $db->query('SELECT * FROM barangay_officials ORDER BY id ASC LIMIT 1')->fetch();

$form = [
    'captain_name'    => $row['captain_name'] ?? '',
    'secretary_name'  => $row['secretary_name'] ?? '',
    'treasurer_name'  => $row['treasurer_name'] ?? '',
    'barangay_name'   => $row['barangay_name'] ?? BARANGAY_NAME,
    'municipality'    => $row['municipality'] ?? DEFAULT_MUNICIPALITY,
    'province'        => $row['province'] ?? DEFAULT_PROVINCE,
    'captain_bio'     => $row['captain_bio'] ?? '',
    'secretary_bio'   => $row['secretary_bio'] ?? '',
    'treasurer_bio'   => $row['treasurer_bio'] ?? '',
    'captain_photo'   => $row['captain_photo'] ?? '',
    'secretary_photo' => $row['secretary_photo'] ?? '',
    'treasurer_photo' => $row['treasurer_photo'] ?? '',
    'official_seal'   => $row['official_seal'] ?? '',
];

function save_official_image(string $field, array $form): ?string
{
    $baseField = str_replace('_upload', '', $field);

    if (empty($_FILES[$field]['name'])) {
        return $form[$baseField] ?? null;
    }

    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return $form[$baseField] ?? null;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed, true)) {
        return $form[$baseField] ?? null;
    }

    $dir = __DIR__ . '/../uploads/officials';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $fileName = $field . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $destination = $dir . '/' . $fileName;

    if (move_uploaded_file($_FILES[$field]['tmp_name'], $destination)) {
        return 'uploads/officials/' . $fileName;
    }

    return $form[$baseField] ?? null;
}

if (is_post()) {
    verify_csrf();

    $form = array_merge($form, Validator::sanitizeArray($_POST));

    $form['captain_photo']   = save_official_image('captain_photo_upload', $form) ?? $form['captain_photo'];
    $form['secretary_photo'] = save_official_image('secretary_photo_upload', $form) ?? $form['secretary_photo'];
    $form['treasurer_photo'] = save_official_image('treasurer_photo_upload', $form) ?? $form['treasurer_photo'];
    $form['official_seal']   = save_official_image('official_seal_upload', $form) ?? $form['official_seal'];

    if ($row) {
        $stmt = $db->prepare("
            UPDATE barangay_officials SET
                captain_name = :captain_name,
                secretary_name = :secretary_name,
                treasurer_name = :treasurer_name,
                barangay_name = :barangay_name,
                municipality = :municipality,
                province = :province,
                captain_bio = :captain_bio,
                secretary_bio = :secretary_bio,
                treasurer_bio = :treasurer_bio,
                captain_photo = :captain_photo,
                secretary_photo = :secretary_photo,
                treasurer_photo = :treasurer_photo,
                official_seal = :official_seal
            WHERE id = :id
        ");

        $stmt->execute([
            'id'              => $row['id'],
            'captain_name'    => $form['captain_name'],
            'secretary_name'  => $form['secretary_name'],
            'treasurer_name'  => $form['treasurer_name'],
            'barangay_name'   => $form['barangay_name'],
            'municipality'    => $form['municipality'],
            'province'        => $form['province'],
            'captain_bio'     => $form['captain_bio'],
            'secretary_bio'   => $form['secretary_bio'],
            'treasurer_bio'   => $form['treasurer_bio'],
            'captain_photo'   => $form['captain_photo'],
            'secretary_photo' => $form['secretary_photo'],
            'treasurer_photo' => $form['treasurer_photo'],
            'official_seal'   => $form['official_seal'],
        ]);
    } else {
        $stmt = $db->prepare("
            INSERT INTO barangay_officials (
                captain_name,
                secretary_name,
                treasurer_name,
                barangay_name,
                municipality,
                province,
                captain_bio,
                secretary_bio,
                treasurer_bio,
                captain_photo,
                secretary_photo,
                treasurer_photo,
                official_seal
            ) VALUES (
                :captain_name,
                :secretary_name,
                :treasurer_name,
                :barangay_name,
                :municipality,
                :province,
                :captain_bio,
                :secretary_bio,
                :treasurer_bio,
                :captain_photo,
                :secretary_photo,
                :treasurer_photo,
                :official_seal
            )
        ");

        $stmt->execute([
            'captain_name'    => $form['captain_name'],
            'secretary_name'  => $form['secretary_name'],
            'treasurer_name'  => $form['treasurer_name'],
            'barangay_name'   => $form['barangay_name'],
            'municipality'    => $form['municipality'],
            'province'        => $form['province'],
            'captain_bio'     => $form['captain_bio'],
            'secretary_bio'   => $form['secretary_bio'],
            'treasurer_bio'   => $form['treasurer_bio'],
            'captain_photo'   => $form['captain_photo'],
            'secretary_photo' => $form['secretary_photo'],
            'treasurer_photo' => $form['treasurer_photo'],
            'official_seal'   => $form['official_seal'],
        ]);
    }

    set_flash('success', 'Officials portfolio updated successfully.');
    redirect('officials/index.php');
}

$pageTitle = 'Officials Portfolio';
$pageSubtitle = 'Edit officials, biographies, upload portfolio images, and set the official seal.';
$pageIcon = 'bi bi-person-badge-fill';
$crumb = 'Home / Officials';
$currentPage = 'officials';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
?>

<div class="row g-4 mb-4">
    <?php
    $cards = [
        [
            'label' => 'Barangay Captain',
            'name'  => $form['captain_name'],
            'bio'   => $form['captain_bio'],
            'photo' => $form['captain_photo'],
        ],
        [
            'label' => 'Barangay Secretary',
            'name'  => $form['secretary_name'],
            'bio'   => $form['secretary_bio'],
            'photo' => $form['secretary_photo'],
        ],
        [
            'label' => 'Barangay Treasurer',
            'name'  => $form['treasurer_name'],
            'bio'   => $form['treasurer_bio'],
            'photo' => $form['treasurer_photo'],
        ],
    ];
    ?>

    <?php foreach ($cards as $card): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body p-4 text-center">
                    <img
                        src="<?= e(!empty($card['photo']) ? url($card['photo']) : url('assets/img/logo-barangay-trx-inspired.svg')) ?>"
                        alt="<?= e($card['label']) ?>"
                        class="official-photo mb-3"
                    >
                    <div class="badge-soft mb-2"><?= e($card['label']) ?></div>
                    <h5 class="fw-bold mb-2"><?= e($card['name'] ?: 'Not set') ?></h5>
                    <p class="text-muted small mb-0"><?= e($card['bio'] ?: 'No biography set yet.') ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Official Seal Preview</h5>
        <?php if (!empty($form['official_seal'])): ?>
            <img
                src="<?= e(url($form['official_seal'])) ?>"
                alt="Official Seal"
                style="width:140px;height:140px;object-fit:contain;background:#fff;border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:10px;"
            >
        <?php else: ?>
            <div
                style="
                    width:140px;
                    height:140px;
                    border:2px dashed rgba(31,164,99,.35);
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    color:#9aa4d6;
                    text-align:center;
                    font-size:.85rem;
                "
            >
                No Seal Uploaded
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Barangay Name</label>
                    <input type="text" name="barangay_name" class="form-control" value="<?= e($form['barangay_name']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Municipality</label>
                    <input type="text" name="municipality" class="form-control" value="<?= e($form['municipality']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Province</label>
                    <input type="text" name="province" class="form-control" value="<?= e($form['province']) ?>">
                </div>

                <div class="col-12"><hr></div>

                <div class="col-md-4">
                    <label class="form-label">Captain Name</label>
                    <input type="text" name="captain_name" class="form-control" value="<?= e($form['captain_name']) ?>">

                    <label class="form-label mt-3">Captain Photo</label>
                    <input type="file" name="captain_photo_upload" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">

                    <label class="form-label mt-3">Captain Bio</label>
                    <textarea name="captain_bio" rows="5" class="form-control"><?= e($form['captain_bio']) ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Secretary Name</label>
                    <input type="text" name="secretary_name" class="form-control" value="<?= e($form['secretary_name']) ?>">

                    <label class="form-label mt-3">Secretary Photo</label>
                    <input type="file" name="secretary_photo_upload" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">

                    <label class="form-label mt-3">Secretary Bio</label>
                    <textarea name="secretary_bio" rows="5" class="form-control"><?= e($form['secretary_bio']) ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Treasurer Name</label>
                    <input type="text" name="treasurer_name" class="form-control" value="<?= e($form['treasurer_name']) ?>">

                    <label class="form-label mt-3">Treasurer Photo</label>
                    <input type="file" name="treasurer_photo_upload" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">

                    <label class="form-label mt-3">Treasurer Bio</label>
                    <textarea name="treasurer_bio" rows="5" class="form-control"><?= e($form['treasurer_bio']) ?></textarea>
                </div>

                <div class="col-12"><hr></div>

                <div class="col-md-6">
                    <label class="form-label">Official Seal</label>
                    <input type="file" name="official_seal_upload" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif">
                    <small class="text-muted d-block mt-2">Recommended: transparent PNG for best certificate appearance.</small>
                </div>
            </div>

            <button class="btn btn-gradient mt-4">Save Portfolio</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>