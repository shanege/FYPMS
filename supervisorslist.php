<?php
require_once 'header.php';
if ($user_data["role"] != "student") {
    exit("You are not allowed to access this page");
}

require_once 'includes/functions-inc.php';

getAllLecturers($con);
