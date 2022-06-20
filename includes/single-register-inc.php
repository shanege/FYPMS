<?php
if (isset($_POST['userID']) && isset($_POST['password'])) {
    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['userID'])) {
        $errors['userID'] = "User ID is a required field.";
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Password is a required field.";
    }

    if (!isset($_POST['role'])) {
        $errors['role'] = "Role is a required field.";
    }

    $password = $_POST['password'];

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
        $errors['password'] = "Passwords must be at least 8 characters long, with uppercase, lowercase letters and numbers.";
    }


    $userID = $_POST['userID'];

    if (userExists($con, $userID) !== false) {
        $errors['userID'] = "This user ID already exists.";
    }

    if (empty($errors)) {
        // updates whenever PHP discovers the hashing algorithm is no longer secure
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = $_POST['role'];

        // prepared statement to create user
        $sql = "INSERT INTO users (userID, password, role) VALUES (?, ?, ?);";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $userID, PDO::PARAM_STR);
            $stmt->bindParam(2, $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(3, $role, PDO::PARAM_STR);

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
    header("location: ../add-users.php");
}
