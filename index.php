<?php
include_once 'header.php';

if ($userData["role"] == "student") {
    include_once 'student/home.php';
} else if ($userData["role"] == "supervisor") {
    include_once 'supervisor/home.php';
} else if ($userData["role"] == "coordinator") {
    include_once 'coordinator/home.php';
}
