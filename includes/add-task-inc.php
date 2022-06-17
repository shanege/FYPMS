<?php

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToWriteFile;

if (isset($_POST['title']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['studentID'])) {
    session_start();

    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['title'])) {
        $errors['title'] = "Title is a required field.";
    }

    if (empty($_POST['date']) || empty($_POST['time'])) {
        $errors['deadline'] = "Deadline is not complete.";
    } else {
        // concatenate the deadline date and time, then instantiate it as a DateTime variable
        $deadlineAt = $_POST['date'] . " " . $_POST['time'] . ":00";
        $deadline = new DateTime($deadlineAt, new DateTimeZone('Asia/Kuala_Lumpur'));

        // get the current DateTime
        $now = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));

        if ($deadline < $now) {
            $errors['deadline'] = "Deadline cannot be in the past.";
        }
    }

    if (empty($_POST['studentID'])) {
        $errors['student'] = "Could not find student.";
    }

    $studentID = $_POST['studentID'];

    // if no errors at this point, run file upload code
    if (empty($errors)) {
        if (is_uploaded_file($_FILES['taskFile']['tmp_name'])) {
            require_once 'functions-inc.php';

            $mimeType = mime_content_type($_FILES['taskFile']['tmp_name']);
            $fileExtension = mimeToExtension($mimeType);

            header("Content-Type:application/" . $fileExtension);

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES['taskFile']['error']) || is_array($_FILES['taskFile']['error'])) {
                $errors['taskFile'] = "Something is wrong with the file.";
            } else if ($fileStream = fopen($_FILES['taskFile']['tmp_name'], 'r')) {
                // create a 10 char long random alphanumeric as the task name
                $bytes = random_bytes(5);
                $randomAlphanumeric = bin2hex($bytes);

                $bucketPath = "Student tasks/" . $studentID . "/Task " . $randomAlphanumeric . '/Supervisor upload/';

                $uploadPath = $bucketPath . basename($_FILES['taskFile']['name']);

                require_once '../adapters/FlySystemAdapter.php';

                try {
                    $filesystem->writeStream($uploadPath, $fileStream);
                } catch (FilesystemException | UnableToWriteFile $exception) {
                    // handle the error
                    $errors['taskFile'] = "Cannot upload file.";
                }
            }
        } else {
            $bucketPath = "";
        }
    }

    // if no errors at this point, run sql code
    if (empty($errors)) {
        $supervisorID = $_SESSION['userID'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = "Ongoing";

        $sql = "INSERT INTO student_tasks 
        (studentID, supervisorID, title, description, deadline_at, supervisor_upload_path, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
            $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);
            $stmt->bindParam(3, $title, PDO::PARAM_STR);
            $stmt->bindParam(4, $description, PDO::PARAM_STR);
            $stmt->bindParam(5, $deadlineAt, PDO::PARAM_STR);
            $stmt->bindParam(6, $bucketPath, PDO::PARAM_STR);
            $stmt->bindParam(7, $status, PDO::PARAM_STR);

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
    header("location: ../manage-supervisees.php");
    exit();
}
