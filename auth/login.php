<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';

if (isset($_SESSION['user'])) {
    redirect('dashboard.php');
}

$db = (new Database())->connect();
$userModel = new User($db);

if ($userModel->countUsers() === 0) {
    redirect('register_admin.php');
}

$error = '';
$username = '';

if (is_post()) {
    verify_csrf();

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($userModel->login($username, $password)) {
        set_flash('success', 'Welcome to Barangay TRX Information System.');
        redirect('dashboard.php');
    }

    $error = 'Invalid username or password.';
}

$pageTitle = 'Login';
$showNavbar = false;
$bodyClass = 'auth-page';

include __DIR__ . '/../includes/header.php';

?>

<div class="auth-wrapper py-5">
    <div class="row g-0 auth-card">

        <div class="col-lg-5 auth-sidebar p-5 d-flex flex-column justify-content-between">

            <div>
                <img src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>" 
                     alt="Barangay TRX logo" 
                     class="auth-logo mb-3">

                <h1 class="display-6 fw-bold">Barangay Information System</h1>

               
<ul class="list-unstyled small mb-0">
    <li class="mb-2">✔ Contact the barangay for emergencies, concerns, and follow-ups through official hotlines</li>
    <li class="mb-2">✔ Get real-time assistance for blotter reports, disputes, and community issues</li>
    <li class="mb-2">✔ Reach barangay staff without visiting in person for faster response and support</li>
    <li class="mb-2">📞 111-222-333 (Main Hotline)</li>
    <li class="mb-2">📞 111-222-334 (Emergency Desk)</li>
    <li>📞 111-222-335 (Blotter & Complaints Desk)</li>
</ul>
            </div>

            <div class="small opacity-75 mt-4">
                Public users can access the client portal from the home page.
            </div>

        </div>

        <div class="col-lg-7 bg-transparent p-4 p-lg-5">

            <div class="mb-4">
                <h2 class="fw-bold mb-1">Admin Sign in</h2>
                <p class="text-muted mb-0">Access the Barangay TRX admin dashboard.</p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" value="<?= e($username) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-gradient btn-lg">Login</button>
                </div>
            </form>

          <div class="mt-4 p-3 rounded-4 text-center d-flex flex-column align-items-center"
     style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06)">

    <div class="fw-semibold">Client Portal</div>

    <div class="small text-muted">
        Got problem? Report it now.
    </div>

    <a href="<?= e(url('client/index.php')) ?>" class="btn btn-soft btn-sm mt-3">
        Open Client Portal
    </a>

</div>

        </div>

    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>