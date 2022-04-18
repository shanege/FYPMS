<?php
session_start();
require_once 'connection-inc.php';

$data = [];
$errors = [];

if (empty($_POST['name'])) {
    $errors['name'] = "Name is a required field.";
}

if (empty($_POST['email'])) {
    $errors['email'] = "Email is a required field.";
}

if (!empty($_POST['name']) && !empty($_POST['email'])) {
    $sql = "UPDATE student_details SET name = ?, email = ?, working_title = ? WHERE studentID = ?";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $_POST['name'], PDO::PARAM_STR);
        $stmt->bindParam(2, $_POST['email'], PDO::PARAM_STR);
        $stmt->bindParam(3, $_POST['workingTitle'], PDO::PARAM_STR);
        $stmt->bindParam(4, $_SESSION['userID'], PDO::PARAM_STR);

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
