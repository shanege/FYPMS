<?php
require_once 'header.php';

if ($userData["role"] == "student") {
    require_once 'student_editprofile.php';
} else if ($userData["role"] == "supervisor") {
    require_once 'supervisor_editprofile.php';
}
