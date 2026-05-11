<?php require_once __DIR__ . '/../config/app.php'; 

require_once __DIR__ . '/../config/Database.php'; 
require_once __DIR__ . '/../classes/User.php'; 


$db = (new Database())->connect(); 
$userModel = new User($db); 
$userModel->logout(); 

set_flash('success', 'You have been logged out.'); 

redirect('auth/login.php'); ?>