<?php
require_once 'includes/connection-inc.php';
require_once 'includes/functions-inc.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$completedStudentsIDs = getAllStudentIDsForSupervisorByStatus($con, $_SESSION['userID'], "Completed");

if (!empty($completedStudentsIDs)) {
    foreach ($completedStudentsIDs as $completedStudentID) {
        $completedStudent = getStudent($con, $completedStudentID['studentID']);

        echo
        '<tr>
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $completedStudentID['studentID'] . '">';

        // if the student has not set up their name in profile, display their studentID, else display their name
        echo ($completedStudent["name"] == "") ?  $completedStudentID['studentID']  :  $completedStudent["name"];
        echo '</div></a></td>
            <td>
                <div class="d-flex w-40">';
        echo ($completedStudent["working_title"] == "") ? 'No working title yet' : $completedStudent["working_title"];
        echo
        '</div></td></tr>';
    }
} else {
    echo '<tr><td colspan="3">No completed students at the moment.</td></tr>';
}
