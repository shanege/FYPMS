<?php
if (isset($_POST['rowNum']) && isset($_POST['studentID'])) {
    $data = [];
    $errors = [];

    session_start();
    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    $students = getAllStudentsForSupervisor($con, $_SESSION['userID']);

    $pendingStudentsIDs = [];

    if (!empty($students)) {
        foreach ($students as $student) {
            if ($student['status'] == "Pending") {
                array_push($pendingStudentsIDs, $student['studentID']);
            }
        }
    } else {
        $errors['empty'] = "No pending students";
    }

    if ($pendingStudentsIDs[$_POST['rowNum']] == $_POST['studentID']) {
        $status = "Ongoing";
        $studentID = $_POST['studentID'];

        $sql = "UPDATE supervisor_student_pairs SET status = ? WHERE studentID = ?";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $status, PDO::PARAM_STR);
            $stmt->bindParam(2, $studentID, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "Quota reached")) {
                $errors['sql'] = "You have hit the quota for FYP students";
            } else {
                $errors['sql'] = $e->getMessage();
            }
        }
    } else {
        $errors['mismatch'] = "There is a mismatch with the database";
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $data['success'] = true;
        $data['message'] = $_POST['studentID'] . ' has been accepted as your FYP student';
    }

    echo json_encode($data);
} else {
    header("location: ../manage-students.php");
    exit();
}
