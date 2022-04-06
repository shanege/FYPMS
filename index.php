<?php
include_once 'header.php';

if ($user_data["role"] == "student") {
    include_once 'student/home.php';
} else if ($user_data["role"] == "supervisor") {
    include_once 'supervisor/home.php';
} else if ($user_data["role"] == "coordinator") {
    include_once 'coordinator/home.php';
}
