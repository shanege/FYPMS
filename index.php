<?php
require_once 'header.php';

if ($userData["role"] == "student") {
    require_once 'student/home.php';
} else if ($userData["role"] == "supervisor") {
    require_once 'supervisor/home.php';
} else if ($userData["role"] == "coordinator") {
    require_once 'coordinator/home.php';
}
