<?php
if (isset($_POST['quota'])) {
    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['semester'])) {
        $errors['semester'] = "Semester is a required field.";
    }

    if (empty($_POST['quota'])) {
        $errors['quota'] = "Quota is a required field.";
    }

    $validSemesters = [];
    $thisYear = date('Y');
    $thisMonth = date('n');

    // if within April and September, set semester as 4, else set as 9
    $semesterMonth = ($thisMonth >= 4 && $thisMonth < 9) ? 4 : 9;

    // add valid semester options to an array
    for ($i = 0; $i < 2; $i++) {
        array_push($validSemesters, $thisYear . "0" . $semesterMonth);

        if ($semesterMonth - 5 == 4) {
            $semesterMonth -= 5;
            array_push($validSemesters, $thisYear . "0" . $semesterMonth);
        }

        $thisYear -= 1;
        $semesterMonth = 9;
    }

    if ($_POST['quota'] <= 0) {
        $errors['quota'] = "Invalid quota.";
    } else if (!in_array($_POST['semester'], $validSemesters)) {
        $errors['semester'] = "Invalid semester.";
    } else {
        $semester = $_POST['semester'];
        $quota = $_POST['quota'];

        $sql = "INSERT INTO supervisor_student_quotas (semester, quota) VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE semester = VALUES(semester), quota = VALUES(quota);";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $semester, PDO::PARAM_INT);
            $stmt->bindParam(2, $quota, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $e) {
            $errors['sql'] = $e->getMessage();
        }
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $data['success'] = true;
        $data['message'] = 'Quota set for ' . $_POST['semester'];
    }

    echo json_encode($data);
} else {
    header("location: ../manage-supervisors.php");
    exit();
}
