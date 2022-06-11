<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [];
    $errors = [];

    session_start();
    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    $proposedTopics = getAllProposedTopicsForSupervisor($con, $_SESSION['userID']);

    $proposedTopicsIDs = [];

    foreach ($proposedTopics as $proposedTopic) {
        array_push($proposedTopicsIDs, $proposedTopic['topicID']);
    }

    if ($proposedTopicsIDs[$_POST['rowNum']] == $_POST['topicID']) {
        $topicID = $_POST['topicID'];

        $sql = "DELETE FROM proposed_topics WHERE topicID = ?";
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindParam(1, $topicID, PDO::PARAM_STR);

            $stmt->execute();
        } catch (PDOException $e) {
            $errors['sql'] = $e->getMessage();
        }
    } else {
        $errors['mismatch'] = "There is a mismatch with the database";
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
