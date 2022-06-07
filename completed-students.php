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

if (!empty($completedStudentsIDs)) {
    foreach ($completedStudentsIDs as $completedStudentID) {
        $completedStudent = getStudent($con, $completedStudentID);

        echo
        '<tr>
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $completedStudentID . '">';

        // if the student has not set up their name in profile, display their studentID, else display their name
        echo ($completedStudent["name"] == "") ?  $completedStudentID  :  $ongoingStudent["name"];
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
