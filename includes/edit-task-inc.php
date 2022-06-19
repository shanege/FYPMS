<?php

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToWriteFile;
use \League\Flysystem\UnableToDeleteDirectory;

if (
    isset($_POST['title']) && isset($_POST['date']) && isset($_POST['time']) && isset($_POST['description'])
    && isset($_POST['studentID']) && isset($_POST['taskID']) && isset($_POST['previousFolder'])
) {
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

    if (empty($_POST['taskID'])) {
        $errors['task'] = "Could not find student.";
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
                // create a 10 char long random alphanumeric as the folder name
                $bytes = random_bytes(5);
                $randomAlphanumeric = bin2hex($bytes);

                $bucketPath = "Student tasks/" . $studentID . "/" . $randomAlphanumeric . '/';

                $uploadPath = $bucketPath . basename($_FILES['taskFile']['name']);

                require_once '../adapters/FlySystemAdapter.php';

                // delete previous upload if there was one
                if ($_POST['previousFolder'] != "") {
                    try {
                        $filesystem->deleteDirectory($_POST['previousFolder']);
                    } catch (FilesystemException | UnableToDeleteDirectory $exception) {
                        // handle the error
                        $errors['submissionFile'] = "Could not delete previous folder.";
                    }
                }

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
        $title = $_POST['title'];
        $description = $_POST['description'];
        $taskID = $_POST['taskID'];

        $sql = "UPDATE student_tasks SET title = ?, description = ?, deadline_at = ?, supervisor_upload_path = ? WHERE taskID = ?;";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $description, PDO::PARAM_STR);
            $stmt->bindParam(3, $deadlineAt, PDO::PARAM_STR);
            $stmt->bindParam(4, $bucketPath, PDO::PARAM_STR);
            $stmt->bindParam(5, $taskID, PDO::PARAM_STR);

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
