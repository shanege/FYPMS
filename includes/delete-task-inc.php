<?php

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToDeleteDirectory;

if (isset($_POST['taskID']) && isset($_POST['supervisorUploadPath']) && isset($_POST['studentSubmitPath'])) {
    session_start();

    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['taskID'])) {
        $errors['task'] = "Could not find task.";
    }

    // if no errors at this point, run file upload code
    if (empty($errors)) {
        require_once '../adapters/FlySystemAdapter.php';

        // delete previous upload if there was one
        if ($_POST['supervisorUploadPath'] != "") {
            try {
                $filesystem->deleteDirectory($_POST['supervisorUploadPath']);
            } catch (FilesystemException | UnableToDeleteDirectory $exception) {
                // handle the error
                $errors['supervisorUploadPath'] = "Could not delete supervisor folder.";
            }
        }

        // delete previous upload if there was one
        if ($_POST['studentSubmitPath'] != "") {
            try {
                $filesystem->deleteDirectory($_POST['studentSubmitPath']);
            } catch (FilesystemException | UnableToDeleteDirectory $exception) {
                // handle the error
                $errors['studentSubmitPath'] = "Could not delete student folder.";
            }
        }
    }

    // if no errors at this point, run sql code
    if (empty($errors)) {
        $taskID = $_POST['taskID'];

        $sql = "DELETE FROM student_tasks WHERE taskID = ?;";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $taskID, PDO::PARAM_STR);

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
