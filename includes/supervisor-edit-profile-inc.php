<?php
if (isset($_POST['saveBtn'])) {
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

    if (empty($_POST['researchAreas'])) {
        $errors['researchAreas'] = "Research area(s) is a required field.";
    }

    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['researchAreas'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $researchAreas = $_POST['researchAreas'];
        $description = $_POST['description'];

        // if this page is accessed by supervisor, supervisorID is their user id
        // if this page is accessed by a coordinator, the supervisorID is taken from the posted supervisorID
        if ($_SESSION['role'] == "supervisor") {
            $supervisorID = $_SESSION['userID'];
        } else if ($_SESSION['role'] == "coordinator") {
            $supervisorID = $_POST['supervisorID'];
        }

        $sql = "UPDATE supervisor_details SET name = ?, email = ?, research_areas = ?, description = ? WHERE supervisorID = ?";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $name, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $stmt->bindParam(3, $researchAreas, PDO::PARAM_STR);
            $stmt->bindParam(4, $description, PDO::PARAM_STR);
            $stmt->bindParam(5, $supervisorID, PDO::PARAM_STR);

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
    header("location: ../profile.php?id=" . $_SESSION['userID']);
    exit();
}
