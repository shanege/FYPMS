<?php
if (isset($_POST['requestingStudentID']) && isset($_POST['requestedSupervisorID'])) {
    require_once 'connection-inc.php';

    $studentID = $_POST['requestingStudentID'];
    $supervisorID = $_POST['requestedSupervisorID'];
    $status = "Pending";

    $sql = "DELETE FROM supervisor_student_pairs WHERE studentID = ? AND supervisorID = ? AND status = ?;";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
        $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);
        $stmt->bindParam(3, $status, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(
            array(
                'success' => true,
                'errors' => "none"
            )
        );
    } catch (PDOException $e) {
        echo json_encode(
            array(
                'success' => false,
                'errors' => $e->getMessage(),
            )
        );
    }
} else {
    header("location: ../index.php");
    exit();
}
