<?php
require_once 'connection-inc.php';

if (isset($_POST['requestingStudentID']) && isset($_POST['requestedSupervisorID'])) {
    $studentID = $_POST['requestingStudentID'];
    $supervisorID = $_POST['requestedSupervisorID'];
    $status = "Pending";

    $sql = "INSERT INTO supervisor_requests (studentID, supervisorID, status) VALUES (?, ?, ?);";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
        $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);
        $stmt->bindParam(3, $status, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(
            array(
                'success' => true,
                'error' => "none"
            )
        );
    } catch (PDOException $e) {
        echo json_encode(
            array(
                'success' => false,
                'error' => $e->getMessage()
            )
        );
    }
}
