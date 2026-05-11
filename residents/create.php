<?php

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Resident.php';
require_once __DIR__ . '/../classes/Validator.php';

$db = (new Database())->connect();
$residentModel = new Resident($db);

$formData = [
    'resident_no' => '',
    'first_name' => '',
    'middle_name' => '',
    'last_name' => '',
    'suffix' => '',
    'sex' => '',
    'civil_status' => '',
    'birth_date' => '',
    'age' => '',
    'address' => '',
    'contact_number' => '',
    'occupation' => '',
    'citizenship' => 'Filipino',
    'years_of_residency' => '0',
    'voter_status' => '',
    'resident_status' => 'Active'
];

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
            $residentModel->create($formData);

            set_flash('success', 'Resident record created successfully.');

            redirect('residents/index.php');

        } catch (PDOException $e) {
            $errors['general'] = 'Unable to save resident record.';
        }
    }
}

$pageTitle = 'Add Resident';
$pageSubtitle = 'Register a new resident profile using the required barangay information fields.';
$pageIcon = 'bi bi-person-plus-fill';
$crumb = 'Home / Residents / Add';

$pageActions = '<a href="' . e(url('residents/index.php')) . '" class="btn btn-soft">Back to List</a>';

$currentPage = 'residents';
$submitLabel = 'Save Resident';

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/page_header.php';
include __DIR__ . '/_form.php';
include __DIR__ . '/../includes/footer.php';

?>