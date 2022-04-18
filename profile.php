<?php
require_once 'header.php';

$id = $_GET['id'];

$user = userExists($con, $id);

if ($user == false) {
    echo '<div class="text-center">This user does not exist</div>';
} else if ($user["role"] == "student") {
    require_once 'student_profile.php';
} else if ($user["role"] == "supervisor") {
    require_once 'supervisor_profile.php';
}
