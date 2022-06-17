<?php
if (isset($_POST['grade']) && isset($_POST['remarks'])) {
    $data = [];
    $errors = [];

    require_once 'connection-inc.php';

    if ($_POST['grade'] == "") {
        $errors['grade'] = "Grade is a required field.";
    }

    if (empty($errors)) {
        $grade = $_POST['grade'];
        $remarks = $_POST['remarks'];
        $taskID = $_POST['taskID'];

        $sql = "UPDATE student_tasks SET grade = ?, remarks = ? WHERE taskID = ?;";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $grade, PDO::PARAM_STR);
            $stmt->bindParam(2, $remarks, PDO::PARAM_STR);
            $stmt->bindParam(3, $taskID, PDO::PARAM_STR);

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
        $data['message'] = 'Success';
    }

    echo json_encode($data);
} else {
    header("location: ../student-tasks.php");
    exit();
}
