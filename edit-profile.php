<?php
require_once 'header.php';

if ($userData["role"] == "student") {
    require_once 'student-edit-profile.php';
} else if ($userData["role"] == "supervisor") {
    require_once 'supervisor-edit-profile.php';
}
