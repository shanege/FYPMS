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
        foreach ($students as $key => $val) {
            if ($val['status'] == "Pending") {
                array_push($pendingStudentsIDs, $val['studentID']);
            }
        }
    } else {
        $errors['empty'] = "No pending students";
    }

    if ($pendingStudentsIDs[$_POST['rowNum']] == $_POST['studentID']) {
        $studentID = $_POST['studentID'];

        $sql = "DELETE FROM supervisor_student_pairs WHERE studentID = ?";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $studentID, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            $errors['sql'] = $e->getMessage();
        }
    } else {
        $errors['mismatch'] = "There is a mismatch with the database";
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors'] = $errors;
    } else {
        $data['success'] = true;
        $data['message'] = $_POST['studentID'] . ' has been rejected';
    }

    echo json_encode($data);
} else {
    header("location: ../manage-students.php");
    exit();
}
