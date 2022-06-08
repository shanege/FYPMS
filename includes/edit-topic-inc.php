<?php
if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    session_start();

    require_once 'connection-inc.php';

    $data = [];
    $errors = [];

    if (empty($_POST['topic'])) {
        $errors['topic'] = "Topic is a required field.";
    }

    if (empty($_POST['description'])) {
        $errors['description'] = "Description is a required field.";
    }

    if (empty($_POST['expectedOutput'])) {
        $errors['expectedOutput'] = "Expected output is a required field.";
    }

    if (empty($_POST['skills'])) {
        $errors['skills'] = "Skills is a required field.";
    }

    if (empty($_POST['fieldOfStudy'])) {
        $errors['fieldOfStudy'] = "Field(s) of study is a required field.";
    }

    if (empty($errors)) {
        $topic = $_POST['topic'];
        $description = $_POST['description'];
        $expectedOutput = $_POST['expectedOutput'];
        $skills = $_POST['skills'];
        $fieldOfStudy = $_POST['fieldOfStudy'];
        $topicID = $_POST['topicID'];

        $sql = "UPDATE proposed_topics SET topic = ?, description = ?, expected_output = ?, skills = ?, field_of_study = ? WHERE topicID = ?;";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $topic, PDO::PARAM_STR);
            $stmt->bindParam(2, $description, PDO::PARAM_STR);
            $stmt->bindParam(3, $expectedOutput, PDO::PARAM_STR);
            $stmt->bindParam(4, $skills, PDO::PARAM_STR);
            $stmt->bindParam(5, $fieldOfStudy, PDO::PARAM_STR);
            $stmt->bindParam(6, $topicID, PDO::PARAM_STR);

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
    header("location: ../propose-topic.php");
    exit();
}
