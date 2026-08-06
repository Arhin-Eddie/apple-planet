<?php
// admin/logout.php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../includes/functions.php';

unset($_SESSION['admin_id']);
redirect(BASE_URL . 'admin/login.php');
