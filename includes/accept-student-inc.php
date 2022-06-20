<?php
if (isset($_POST['studentID'])) {
    $data = [];
    $errors = [];

    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    if (empty($_POST['studentID'])) {
        $errors['empty'] = "Could not find student.";
    }

    if (empty($errors)) {
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
