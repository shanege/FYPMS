<?php
include_once 'header.php';

if ($userData["role"] == "student") {
    include_once 'student_profile.php';
} else if ($userData["role"] == "supervisor") {
    include_once 'supervisor_profile.php';
} else if ($userData["role"] == "coordinator") {
    include_once 'coordinator_profile.php';
}
