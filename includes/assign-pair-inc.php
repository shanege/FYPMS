<?php
if (isset($_POST['assign'])) {
    $data = [];
    $errors = [];

    require_once 'connection-inc.php';

    if (empty($_POST['studentID'])) {
        $errors['studentID'] = "Could not find student.";
    }

    if (empty($_POST['supervisorID'])) {
        $errors['supervisorID'] = "Could not find supervisor.";
    }

    if (empty($errors)) {
        $studentID = $_POST['studentID'];
        $supervisorID = $_POST['supervisorID'];

        // get current semester
        $thisYear = date('Y');
        $thisMonth = date('n');

        // if within April and September, set semester as 4, else set as 9
        $semesterMonth = ($thisMonth >= 4 && $thisMonth < 9) ? 4 : 9;
        $startingSemester = $thisYear . "0" . $semesterMonth;

        $status = "Ongoing";

        $sql = "INSERT INTO supervisor_student_pairs (studentID, supervisorID, starting_semester, status) VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE studentID = VALUES(studentID), supervisorID = VALUES(supervisorID), starting_semester = VALUES(starting_semester), status = VALUES(status);";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
            $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);
            $stmt->bindParam(3, $startingSemester, PDO::PARAM_STR);
            $stmt->bindParam(4, $status, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "Quota reached")) {
                $errors['sql'] = $supervisorID . ' has hit the quota for FYP students';
            } else {
                $errors['sql'] = $e->getMessage();
            }
        }
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $data['success'] = true;
        $data['message'] = 'Success';
    }

    echo json_encode($data);
} else {
    header("location: ../manage-students.php");
    exit();
}
