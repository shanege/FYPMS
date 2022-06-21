<?php

use \League\Flysystem\FilesystemException;
use \League\Flysystem\UnableToWriteFile;
use \League\Flysystem\UnableToDeleteFile;

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['student'])) {
        $errors['student'] = "Student is a required field.";
    }

    if ($_FILES['archiveFile']['name'] == '') {
        $errors['archiveFile'] = "Thesis file is a required field.";
    }

    // if no errors at this point, run file upload code
    if (empty($errors)) {
        if (is_uploaded_file($_FILES['archiveFile']['tmp_name'])) {
            require_once 'functions-inc.php';

            $mimeType = mime_content_type($_FILES['archiveFile']['tmp_name']);
            $fileExtension = mimeToExtension($mimeType);

            header("Content-Type:application/" . $fileExtension);

            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES['archiveFile']['error']) || is_array($_FILES['archiveFile']['error'])) {
                $errors['archiveFile'] = "Something is wrong with the file.";
            } else if ($fileStream = fopen($_FILES['archiveFile']['tmp_name'], 'r')) {
                $studentID = $_POST['student'];

                $previousBucketPath = getThesisBucketPath($con, $studentID);

                $uploadPath = "Thesis archive/" . $studentID . "/" . basename($_FILES['archiveFile']['name']);

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
                try {
                    $filesystem->writeStream($uploadPath, $fileStream);
                } catch (FilesystemException | UnableToWriteFile $exception) {
                    // handle the error
                    $errors['archiveFile'] = "Cannot upload file.";
                }
            }
        }
    }

    // if no errors at this point, run sql code
    if (empty($errors)) {

        $sql = "INSERT INTO student_theses (studentID, thesis_bucket_path) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE studentID = VALUES(studentID), thesis_bucket_path = VALUES(thesis_bucket_path);";

        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
            $stmt->bindParam(2, $uploadPath, PDO::PARAM_STR);

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
    header("location: ../thesis-archive.php");
    exit();
}
