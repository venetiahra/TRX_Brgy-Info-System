<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$residentModel = new Resident($db);

$id = (int) ($_GET['id'] ?? 0);

$resident = $residentModel->getById($id);

if (!$resident) {
    set_flash('danger', 'Resident record not found.');
    redirect('residents/index.php');
}

$formData = $resident;
$errors = [];

if (is_post()) {

    verify_csrf();

    $formData = array_merge($formData, Validator::sanitizeArray($_POST));

    $errors = Validator::validateRequired(
        $formData,
        [
            'resident_no' => 'Resident number',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'sex' => 'Sex',
            'birth_date' => 'Birth date',
            'address' => 'Address'
        ]
    );

    if (empty($errors)) {
        try {
            $residentModel->update($id, $formData);

            set_flash('success', 'Resident record updated successfully.');

            redirect('residents/view.php?id=' . $id);

        } catch (PDOException $e) {
            $errors['general'] = 'Unable to update resident record.';
        }
    }
}

$pageTitle = 'Edit Resident';
$pageSubtitle = 'Update the selected resident profile while preserving your project logic.';
$pageIcon = 'bi bi-pencil-square';
$crumb = 'Home / Residents / Edit';

$pageActions = '<a href="' . e(url('residents/view.php?id=' . $id)) . '" class="btn btn-soft">Back to Profile</a>';

$currentPage = 'residents';
$submitLabel = 'Update Resident';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../includes/footer.php';

?>