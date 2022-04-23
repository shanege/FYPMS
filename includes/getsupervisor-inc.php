<?php
if (isset($_POST['supervisorID'])) {
    require_once "functions-inc.php";
    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    $supervisorID = $_POST['supervisorID'];
    $result = getSupervisor($con, $_POST['supervisorID']);

    echo json_encode($result);
} else {
    header("location: ../managesupervisors.php");
    exit();
}
