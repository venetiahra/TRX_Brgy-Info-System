<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';

$db = (new Database())->connect();
$officials = $db->query('SELECT * FROM barangay_officials ORDER BY id ASC LIMIT 1')->fetch();

$pageTitle = 'Barangay Officials';
$activePage = 'officials';

include __DIR__ . '/includes/header.php';
?>

<style>
.officials-hero {
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(31,164,99,.10), transparent 25%),
        radial-gradient(circle at bottom left, rgba(21,115,214,.12), transparent 25%),
        linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
    border: 1px solid #dce6f5;
    box-shadow: 0 18px 40px rgba(16,36,84,.08);
}
.official-badge {
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
.official-card-premium {
    border-radius: 24px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 14px 30px rgba(16,36,84,.06);
    height: 100%;
}
.official-photo-large {
    width: 132px;
    height: 132px;
    object-fit: cover;
    border-radius: 22px;
    border: 1px solid #dce6f5;
    box-shadow: 0 12px 24px rgba(16,36,84,.08);
    background: #fff;
}
.office-info-box {
    border-radius: 24px;
    background: linear-gradient(135deg, #0B2E83 0%, #1573D6 100%);
    color: #fff;
}
.info-label {
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .05rem;
    opacity: .8;
}
.info-value {
    font-size: 1rem;
    font-weight: 700;
}
</style>

<div class="officials-hero p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <div class="official-badge mb-3">
                <i class="bi bi-person-badge-fill"></i>
                Barangay Leadership
            </div>
            <h1 class="display-6 fw-bold mb-2" style="color:#0B2E83;">Meet the Barangay Officials</h1>
            <p class="text-muted-client mb-0">
                Public-facing profile page for the current barangay officials and office information.
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <img
                src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>"
                alt="Barangay TRX logo"
                class="client-logo"
                style="width:96px;height:96px;"
            >
        </div>
    </div>
</div>

<?php
$cards = [
    [
        'label' => 'Barangay Captain',
        'name'  => $officials['captain_name'] ?? 'Not set',
        'bio'   => $officials['captain_bio'] ?? 'No biography set yet.',
        'photo' => $officials['captain_photo'] ?? '',
    ],
    [
        'label' => 'Barangay Secretary',
        'name'  => $officials['secretary_name'] ?? 'Not set',
        'bio'   => $officials['secretary_bio'] ?? 'No biography set yet.',
        'photo' => $officials['secretary_photo'] ?? '',
    ],
    [
        'label' => 'Barangay Treasurer',
        'name'  => $officials['treasurer_name'] ?? 'Not set',
        'bio'   => $officials['treasurer_bio'] ?? 'No biography set yet.',
        'photo' => $officials['treasurer_photo'] ?? '',
    ],
];
?>

<div class="row g-4 mb-4">
    <?php foreach ($cards as $card): ?>
        <div class="col-md-4">
            <div class="official-card-premium p-4 text-center">
                <img
                    src="<?= e(!empty($card['photo']) ? url($card['photo']) : url('assets/img/logo-barangay-trx-inspired.svg')) ?>"
                    alt="<?= e($card['label']) ?>"
                    class="official-photo-large mb-3"
                >
                <div class="badge-soft mb-2"><?= e($card['label']) ?></div>
                <h5 class="fw-bold mb-2"><?= e($card['name']) ?></h5>
                <p class="text-muted-client small mb-0"><?= e($card['bio']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="office-info-box p-4 p-lg-5">
    <h5 class="fw-bold mb-4">Office Information</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="info-label">Barangay Name</div>
            <div class="info-value"><?= e($officials['barangay_name'] ?? BARANGAY_NAME) ?></div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Municipality</div>
            <div class="info-value"><?= e($officials['municipality'] ?? DEFAULT_MUNICIPALITY) ?></div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Province</div>
            <div class="info-value"><?= e($officials['province'] ?? DEFAULT_PROVINCE) ?></div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>