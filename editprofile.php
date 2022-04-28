<?php
require_once 'header.php';

if ($userData["role"] == "student") {
    require_once 'student-editprofile.php';
} else if ($userData["role"] == "supervisor") {
    require_once 'supervisor-editprofile.php';
}
