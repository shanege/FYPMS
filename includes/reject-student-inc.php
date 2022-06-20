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
