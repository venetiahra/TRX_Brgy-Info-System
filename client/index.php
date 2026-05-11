<?php
require_once __DIR__ . '/../config/app.php';
$pageTitle = 'Client Portal';
$activePage = 'home';
include __DIR__ . '/includes/header.php';
?>

<style>
.hero-premium {
    position: relative;
    overflow: hidden;
    border-radius: 28px;
    background:
        radial-gradient(circle at top right, rgba(31,164,99,.14), transparent 25%),
        radial-gradient(circle at bottom left, rgba(21,115,214,.14), transparent 28%),
        linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%);
    border: 1px solid rgba(21,115,214,.08);
    box-shadow: 0 18px 40px rgba(16,36,84,.08);
}

.hero-premium::before {
    content: "";
    position: absolute;
    inset: auto -60px -60px auto;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(31,164,99,.12), rgba(21,115,214,.12));
}

.hero-badge {
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

.hero-title {
    font-size: clamp(2rem, 4vw, 3.2rem);
    line-height: 1.05;
    font-weight: 800;
    color: #0B2E83;
}

.hero-lead {
    font-size: 1.05rem;
    color: #5f6f94;
    max-width: 640px;
}

.hero-logo-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 260px;
    height: 260px;
    border-radius: 28px;
    background: linear-gradient(135deg, rgba(21,115,214,.08), rgba(31,164,99,.08));
    border: 1px solid rgba(21,115,214,.08);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.7);
}

.hero-logo-wrap img {
    width: 180px;
    height: 180px;
    object-fit: contain;
}

.stat-chip {
    display: flex;
    align-items: center;
    gap: .85rem;
    padding: 1rem 1.1rem;
    border-radius: 18px;
    background: rgba(255,255,255,.78);
    border: 1px solid rgba(21,115,214,.08);
    box-shadow: 0 8px 20px rgba(16,36,84,.05);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: grid;
    place-items: center;
    color: #fff;
    font-size: 1.15rem;
    flex-shrink: 0;
}

.stat-green { background: linear-gradient(135deg, #1FA463, #66D08F); }
.stat-blue { background: linear-gradient(135deg, #1573D6, #55A0FF); }
.stat-indigo { background: linear-gradient(135deg, #5D7CFA, #8EA2FF); }

.stat-label {
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .06rem;
    color: #7a86a8;
    font-weight: 700;
}

.stat-value {
    font-size: 1.15rem;
    font-weight: 800;
    color: #17315f;
    line-height: 1.1;
}

.service-card {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background: #fff;
    border: 1px solid #dce6f5;
    box-shadow: 0 16px 32px rgba(16,36,84,.07);
    height: 100%;
    transition: transform .18s ease, box-shadow .18s ease;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 38px rgba(16,36,84,.11);
}

.service-card::after {
    content: "";
    position: absolute;
    inset: auto -40px -40px auto;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: rgba(21,115,214,.06);
}

.service-icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    font-size: 1.35rem;
    color: #fff;
    margin-bottom: 1rem;
}

.service-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #17315f;
}

.service-text {
    color: #6f7b9f;
    min-height: 52px;
}

.info-card {
    border-radius: 24px;
    background: linear-gradient(135deg, #0B2E83 0%, #1573D6 100%);
    color: #fff;
    overflow: hidden;
    position: relative;
}

.info-card::before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.09);
}

.info-title {
    font-size: 1.4rem;
    font-weight: 800;
}

.info-list li {
    margin-bottom: .7rem;
}

.quick-link-pill {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .6rem .9rem;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.16);
    color: #fff;
    font-weight: 600;
}
</style>

<section class="hero-premium p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <div class="hero-badge mb-3">
                <i class="bi bi-stars"></i>
                Fast • Easy • Barangay Online Services
            </div>

            <h1 class="hero-title mb-3">
                Request and track barangay services online
            </h1>

            <p class="hero-lead mb-4">
                Use the client portal to request certificates, track requests, submit blotter reports,
                and view barangay officials — all in one modern service hub.
            </p>

            <div class="d-flex flex-wrap gap-2 mb-4">
                <a href="<?= e(url('client/request.php')) ?>" class="btn btn-client-gradient btn-lg">
                    <i class="bi bi-file-earmark-plus-fill me-2"></i>Request Certificate
                </a>

                <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline btn-lg">
                    <i class="bi bi-search me-2"></i>Track Request
                </a>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="stat-chip">
                        <div class="stat-icon stat-green">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <div>
                            <div class="stat-label">Certificates</div>
                            <div class="stat-value">Online Request</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-chip">
                        <div class="stat-icon stat-blue">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <div class="stat-label">Blotter</div>
                            <div class="stat-value">Case Tracking</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-chip">
                        <div class="stat-icon stat-indigo">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <div class="stat-label">Officials</div>
                            <div class="stat-value">Public Profiles</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 text-center">
            <div class="hero-logo-wrap mx-auto">
                <img src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>" alt="Barangay TRX logo">
            </div>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="service-card p-4">
            <div class="service-icon stat-green">
                <i class="bi bi-file-earmark-plus-fill"></i>
            </div>
            <h3 class="service-title mb-2">Request Certificate</h3>
            <p class="service-text mb-3">
                Submit certificate requests quickly through the client portal.
            </p>
            <a href="<?= e(url('client/request.php')) ?>" class="btn btn-client-gradient w-100">
                Open Request Form
            </a>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="service-card p-4">
            <div class="service-icon stat-blue">
                <i class="bi bi-search"></i>
            </div>
            <h3 class="service-title mb-2">Track Request</h3>
            <p class="service-text mb-3">
                Search and monitor certificate requests by control number or resident name.
            </p>
            <a href="<?= e(url('client/history.php')) ?>" class="btn btn-client-outline w-100">
                Open Tracker
            </a>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="service-card p-4">
            <div class="service-icon stat-indigo">
                <i class="bi bi-journal-text"></i>
            </div>
            <h3 class="service-title mb-2">Submit Blotter</h3>
            <p class="service-text mb-3">
                File a blotter report and receive a control number for follow-up.
            </p>
            <a href="<?= e(url('client/blotter.php')) ?>" class="btn btn-client-outline w-100">
                Open Blotter
            </a>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="service-card p-4">
            <div class="service-icon stat-green">
                <i class="bi bi-people-fill"></i>
            </div>
            <h3 class="service-title mb-2">View Officials</h3>
            <p class="service-text mb-3">
                See the current barangay officials and office information.
            </p>
            <a href="<?= e(url('client/officials.php')) ?>" class="btn btn-client-outline w-100">
                Open Officials
            </a>
        </div>
    </div>
</section>

<section class="info-card p-4 p-lg-5 mb-4">
    <div class="row g-4 align-items-center">
        <div class="col-lg-7">
            <h2 class="info-title mb-3">Barangay Hotline & Assistance</h2>
            <ul class="info-list mb-0 ps-3">
                <li>Contact the barangay quickly for concerns, emergencies, and follow-ups through our hotline.</li>
                <li>Get assistance for blotter reports and community issues anytime.</li>
                <li>Hotline: 111-222-333 | 111-222-334 | 111-222-335</li>
            </ul>
        </div>

        <div class="col-lg-5">
            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <span class="quick-link-pill"><i class="bi bi-check-circle-fill"></i> Easy Navigation</span>
                <span class="quick-link-pill"><i class="bi bi-shield-check"></i> Secured</span>
                <span class="quick-link-pill"><i class="bi bi-lightning-charge-fill"></i> Fast Solution</span>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>