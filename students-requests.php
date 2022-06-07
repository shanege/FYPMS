<?php
require_once 'includes/connection-inc.php';
require_once 'includes/functions-inc.php';

$students = getAllStudentsForSupervisor($con, $_POST['userID']);

$pendingStudentsIDs = [];
$ongoingStudentsIDs = [];
$completedStudentsIDs = [];

if (!empty($students)) {
    foreach ($students as $student) {
        if ($student['status'] == "Pending") {
            array_push($pendingStudentsIDs, $student['studentID']);
        } else if ($student['status'] == "Ongoing") {
            array_push($ongoingStudentsIDs, $student['studentID']);
        } else if ($student['status'] == "Completed") {
            array_push($completedStudentsIDs, $student['studentID']);
        }
    }
}

if (!empty($pendingStudentsIDs)) {
    foreach ($pendingStudentsIDs as $pendingStudentID) {
        $student = getStudent($con, $pendingStudentID);

        echo
        '<tr id="' . $pendingStudentID . '">
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $pendingStudentID . '">';

        // if the student has not set up their name in profile, display their studentID, else display their name
        echo ($student["name"] == "") ?  $pendingStudentID  :  $student["name"];
        echo
        '</div></a></td>
            <td>
                <div class="d-flex w-40">';
        echo ($student["working_title"] == "") ? 'No working title yet' : $student["working_title"];
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
