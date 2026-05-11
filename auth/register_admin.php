<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Validator.php';
$db = (new Database())->connect();
$userModel = new User($db);
$existingUsers = $userModel->countUsers();
$formData = ['fullname' => 'Barangay TRX Administrator', 'username' => 'admin', 'password' => 'admin123', 'confirm_password' => 'admin123'];
$errors = [];
if ($existingUsers === 0 && is_post()) {
    verify_csrf();
    $formData = array_merge($formData, Validator::sanitizeArray($_POST));
    $errors = Validator::validateRequired($formData, ['fullname' => 'Full name', 'username' => 'Username', 'password' => 'Password', 'confirm_password' => 'Confirm password']);
    if (($formData['password'] ?? '') !== ($formData['confirm_password'] ?? '')) $errors['confirm_password'] = 'Passwords do not match.';
    if (strlen((string) ($formData['password'] ?? '')) < 6) $errors['password'] = 'Password must be at least 6 characters.';
    if ($userModel->findUserByUsername((string) ($formData['username'] ?? ''))) $errors['username'] = 'That username is already taken.';
    if (empty($errors)) {
        $userModel->createDefaultAdmin((string) $formData['fullname'], (string) $formData['username'], (string) $formData['password']);
        set_flash('success', 'Administrator account created. You can now log in.');
        redirect('auth/login.php');
    }
}
$pageTitle = 'Register Admin';
$showNavbar = false;
$bodyClass = 'auth-page';
include __DIR__ . '/../includes/header.php';
?>
<div class="auth-wrapper py-5"><div class="row g-0 auth-card"><div class="col-lg-5 auth-sidebar p-5 d-flex flex-column justify-content-between"><div><img src="<?= e(url('assets/img/logo-barangay-trx-inspired.svg')) ?>" alt="Barangay TRX logo" class="auth-logo mb-3"><h1 class="display-6 fw-bold">Create Administrator</h1><p class="mb-4">Use this page once to initialize the first system administrator.</p><div class="small opacity-75">Suggested defaults: <strong>admin / admin123</strong></div></div><div class="small opacity-75">After setup, the client portal is available at /client/index.php</div></div><div class="col-lg-7 bg-transparent p-4 p-lg-5"><div class="mb-4"><h2 class="fw-bold mb-1">Administrator Registration</h2><p class="text-muted mb-0">Available only when no user exists in the database.</p></div><?php if ($existingUsers > 0): ?><div class="alert alert-info">An administrator or staff account already exists in the database.</div><a href="<?= e(url('auth/login.php')) ?>" class="btn btn-gradient">Go to Login</a><?php else: ?><form method="POST"><?= csrf_field() ?><div class="row g-3"><div class="col-12"><label class="form-label fw-semibold">Full name</label><input type="text" name="fullname" class="form-control <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>" value="<?= e($formData['fullname']) ?>"><div class="invalid-feedback"><?= e($errors['fullname'] ?? '') ?></div></div><div class="col-md-6"><label class="form-label fw-semibold">Username</label><input type="text" name="username" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" value="<?= e($formData['username']) ?>"><div class="invalid-feedback"><?= e($errors['username'] ?? '') ?></div></div><div class="col-md-6"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" value="<?= e($formData['password']) ?>"><div class="invalid-feedback"><?= e($errors['password'] ?? '') ?></div></div><div class="col-12"><label class="form-label fw-semibold">Confirm password</label><input type="password" name="confirm_password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" value="<?= e($formData['confirm_password']) ?>"><div class="invalid-feedback"><?= e($errors['confirm_password'] ?? '') ?></div></div></div><div class="d-flex gap-2 mt-4 flex-wrap"><button type="submit" class="btn btn-gradient">Create Admin</button><a href="<?= e(url('client/index.php')) ?>" class="btn btn-soft">Open Client Portal</a></div></form><?php endif; ?></div></div></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
