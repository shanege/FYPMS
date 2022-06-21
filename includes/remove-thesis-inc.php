<?php

use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToDeleteFile;

if (($_SERVER['REQUEST_METHOD'] === 'POST')) {
    $data = [];
    $errors = [];

    require_once 'connection-inc.php';

    if (empty($_POST['studentID'])) {
        $errors['studentID'] = "Could not find studentID.";
    }

    // if no errors at this point, run file upload code
    if (empty($errors)) {
        require_once 'functions-inc.php';

        $studentID = $_POST['studentID'];

        $previousBucketPath = getThesisBucketPath($con, $studentID);

        require_once '../adapters/FlySystemAdapter.php';

        // delete previous upload if there was one
        if ($previousBucketPath !== false) {
            try {
                $filesystem->delete($previousBucketPath);
            } catch (FilesystemException | UnableToDeleteFile $exception) {
                // handle the error
                $errors['archiveFile'] = "Could not delete previous file.";
            }
        }
    }

    if (empty($errors)) {
        $sql = "DELETE FROM student_theses WHERE studentID = ?";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $studentID, PDO::PARAM_STR);

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
        $data['message'] = 'Topic has been removed';
    }

    echo json_encode($data);
} else {
    header("location: ../manage-topics.php");
    exit();
}
