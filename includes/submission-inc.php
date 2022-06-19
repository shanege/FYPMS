<?php

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToWriteFile;
use \League\Flysystem\UnableToDeleteDirectory;

if (isset($_POST['submissionText'])) {
    session_start();

    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    if ($_FILES['submissionFile']['name'] == '' && empty($_POST['submissionText'])) {
        $errors['submission'] = "At least one of these must be filled.";
    }

    if (empty($_POST['taskID'])) {
        $errors['task'] = "Could not find task.";
    }

    $taskID = $_POST['taskID'];
    $studentID = $_SESSION['userID'];

    // if no errors at this point, run file upload code
    if (empty($errors)) {
        if (is_uploaded_file($_FILES['submissionFile']['tmp_name'])) {
            require_once 'functions-inc.php';

            $mimeType = mime_content_type($_FILES['submissionFile']['tmp_name']);
            $fileExtension = mimeToExtension($mimeType);

            header("Content-Type:application/" . $fileExtension);

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES['submissionFile']['error']) || is_array($_FILES['submissionFile']['error'])) {
                $errors['submissionFile'] = "Something is wrong with the file.";
            } else if ($fileStream = fopen($_FILES['submissionFile']['tmp_name'], 'r')) {
                // create a 10 char long random alphanumeric as the task name
                $bytes = random_bytes(5);
                $randomAlphanumeric = bin2hex($bytes);

                $bucketPath = "Student tasks/" . $studentID . "/" . $randomAlphanumeric . '/';

                $uploadPath = $bucketPath . basename($_FILES['submissionFile']['name']);

                require_once '../adapters/FlySystemAdapter.php';

                // delete previous upload if there was one
                // this is for cases where the student edits their submission
                if ($_POST['previousFolder'] != "") {
                    try {
                        $filesystem->deleteDirectory($_POST['previousFolder']);
                    } catch (FilesystemException | UnableToDeleteDirectory $exception) {
                        // handle the error
                        $errors['submissionFile'] = "Could not delete previous folder.";
                    }
                }

                if (empty($errors)) {
                    try {
                        $filesystem->writeStream($uploadPath, $fileStream);
                    } catch (FilesystemException | UnableToWriteFile $exception) {
                        // handle the error
                        $errors['submissionFile'] = "Cannot upload file.";
                    }
                }
            }
        } else {
            $bucketPath = "";
        }
    }

    // if no errors at this point, run sql code
    if (empty($errors)) {
        $submissionText = $_POST['submissionText'];
        $status = "Completed";

        $sql = "UPDATE student_tasks SET student_submit_path = ?, submission_text = ?, status = ? WHERE taskID = ?;";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $bucketPath, PDO::PARAM_STR);
            $stmt->bindParam(2, $submissionText, PDO::PARAM_STR);
            $stmt->bindParam(3, $status, PDO::PARAM_STR);
            $stmt->bindParam(4, $taskID, PDO::PARAM_STR);

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
