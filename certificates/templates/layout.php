<?php
$officials = $requestData['officials'] ?? [];

$barangayName = $officials['barangay_name'] ?? BARANGAY_NAME;
$municipality = $officials['municipality'] ?? DEFAULT_MUNICIPALITY;
$province = $officials['province'] ?? DEFAULT_PROVINCE;

$captainName = $officials['captain_name'] ?? 'Barangay Captain';
$secretaryName = $officials['secretary_name'] ?? 'Barangay Secretary';

/*
|--------------------------------------------------------------------------
| OFFICIAL SEAL PATH
|--------------------------------------------------------------------------
| Uses DB path first. If that file doesn't exist, fallback to:
| uploads/officials/official_seal.jpg
*/
$sealPath = trim((string)($officials['official_seal'] ?? ''));
$fallbackSeal = 'uploads/officials/official_seal.jpg';

$sealExists = false;

if ($sealPath !== '') {
    $sealFs = __DIR__ . '/../../' . ltrim($sealPath, '/');
    if (is_file($sealFs)) {
        $sealExists = true;
    }
}

if (!$sealExists) {
    $fallbackFs = __DIR__ . '/../../' . $fallbackSeal;
    if (is_file($fallbackFs)) {
        $sealPath = $fallbackSeal;
        $sealExists = true;
    } else {
        $sealPath = '';
    }
}
?>

<div class="certificate-sheet">
    <div class="certificate-banner">
        <img
            src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>"
            alt="Barangay TRX seal"
            class="certificate-logo"
        >

        <div class="text-center flex-grow-1">
            <div>Republic of the Philippines</div>
            <div>Province of <?= e($province) ?></div>
            <div>Municipality of <?= e($municipality) ?></div>
            <div class="fw-bold"><?= e($barangayName) ?></div>
            <div>Office of the Barangay Captain</div>
        </div>

        <div class="text-center" style="width:120px;">
            <?php if ($sealExists && $sealPath !== ''): ?>
                <img
                    src="<?= e(url($sealPath)) ?>"
                    alt="Official Seal"
                    style="width:110px;height:110px;object-fit:contain;"
                >
            <?php else: ?>
                <div
                    style="
                        width:110px;
                        height:110px;
                        border:2px dashed rgba(31,164,99,.35);
                        border-radius:50%;
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        color:#7c83a7;
                        font-size:.8rem;
                        text-align:center;
                    "
                >
                    Official Seal
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mb-4">
        <h2 class="certificate-title mb-2">
            <?= e(strtoupper($requestData['certificate_type'] ?? 'CERTIFICATE')) ?>
        </h2>
        <div class="certificate-control">
            Control No.: <?= e($requestData['control_no'] ?? '') ?>
        </div>
    </div>

    <div class="certificate-body">
        <p><strong>TO WHOM IT MAY CONCERN:</strong></p>

        <p class="indent">
            This is to certify that
            <strong><?= e($requestData['resident_full_name'] ?? '') ?></strong>,
            a resident of
            <strong><?= e($requestData['address'] ?? '') ?></strong>,
            is hereby issued this certification based on available barangay record and verification.
        </p>

        <p class="indent">
            This document is issued upon request for
            <strong><?= e($requestData['purpose'] ?? '') ?></strong>
            and for any other lawful purpose it may serve.
        </p>

        <p class="indent">
            Issued this
            <strong><?= e(format_date_human($requestData['date_issued'] ?? null)) ?></strong>
            at
            <strong><?= e($barangayName) ?></strong>,
            <strong><?= e($municipality) ?></strong>,
            <strong><?= e($province) ?></strong>,
            upon the request of the above-named person for
            <strong><?= e($requestData['purpose'] ?? '') ?></strong>.
        </p>
    </div>

    <div class="certificate-meta mt-4">
        <div>
            <span>Resident Name</span>
            <strong><?= e($requestData['resident_full_name'] ?? '') ?></strong>
        </div>

        <div>
            <span>Address</span>
            <strong><?= e($requestData['address'] ?? '') ?></strong>
        </div>

        <div>
            <span>OR Number</span>
            <strong><?= e($requestData['or_no'] ?? 'N/A') ?></strong>
        </div>

        <div>
            <span>Issued By</span>
            <strong><?= e($requestData['issued_by'] ?? 'Barangay Staff') ?></strong>
        </div>

        <div style="grid-column:1 / -1;">
            <span>Remarks</span>
            <strong><?= e($requestData['remarks'] ?? 'N/A') ?></strong>
        </div>
    </div>

    <div class="signature-grid">
        <div class="signature-card">
            <div class="signature-line"></div>
            <div class="fw-bold"><?= e($secretaryName) ?></div>
            <div>Barangay Secretary</div>
        </div>

        <div class="signature-card">
            <div class="signature-line"></div>
            <div class="fw-bold"><?= e($captainName) ?></div>
            <div>Barangay Captain</div>
