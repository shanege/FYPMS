<?php
require_once 'includes/connection-inc.php';
require_once 'includes/functions-inc.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pendingStudentsIDs = getAllStudentIDsForSupervisorByStatus($con, $_SESSION['userID'], "Pending");

if (!empty($pendingStudentsIDs)) {
    foreach ($pendingStudentsIDs as $pendingStudentID) {
        $pendingStudent = getStudent($con, $pendingStudentID['studentID']);

        echo
        '<tr id="' . $pendingStudentID['studentID'] . '">
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $pendingStudentID['studentID'] . '">';

        // if the student has not set up their name in profile, display their studentID, else display their name
        echo ($pendingStudent["name"] == "") ?  $pendingStudentID['studentID']  :  $pendingStudent["name"];
        echo '</div></a></td>
            <td>
                <div class="d-flex w-40">';
        echo ($pendingStudent["working_title"] == "") ? 'No working title yet' : $pendingStudent["working_title"];
        echo
        '</div>
            </td>
            <td>
                <div class="d-flex justify-content-evenly">
                    <button type="button" class="btn btn-success"><i class="bi bi-person-check"></i>&nbsp;Accept</button>
                    <button type="button" class="btn btn-danger"><i class="bi bi-person-x"></i>&nbsp;Decline</button>
                </div>
            </td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="3">No student requests at the moment.</td></tr>';
}
