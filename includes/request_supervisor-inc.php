<?php
if (isset($_POST['requestingStudentID']) && isset($_POST['requestedSupervisorID'])) {
    require_once 'connection-inc.php';

    $studentID = $_POST['requestingStudentID'];
    $supervisorID = $_POST['requestedSupervisorID'];
    $status = "Pending";

    // get current semester
    $thisYear = date('Y');
    $thisMonth = date('n');

    // if within April and September, set semester as 4, else set as 9
    $semesterMonth = ($thisMonth >= 4 && $thisMonth < 9) ? 4 : 9;
    $startingSemester = $thisYear . "0" . $semesterMonth;

    $sql = "INSERT INTO supervisor_student_pairs (studentID, supervisorID, starting_semester, status) VALUES (?, ?, ?, ?);";
    try {
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $studentID, PDO::PARAM_STR);
        $stmt->bindParam(2, $supervisorID, PDO::PARAM_STR);
        $stmt->bindParam(3, $startingSemester, PDO::PARAM_INT);
        $stmt->bindParam(4, $status, PDO::PARAM_STR);
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
                'errors' => $e->getMessage()
            )
        );
    }
} else {
    header("location: ../index.php");
    exit();
}
