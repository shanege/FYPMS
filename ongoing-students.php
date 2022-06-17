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

if (!empty($ongoingStudentsIDs)) {
    foreach ($ongoingStudentsIDs as $ongoingStudentID) {
        $ongoingStudent = getStudent($con, $ongoingStudentID);

        echo
        '<tr>
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $ongoingStudentID . '">';

        // if the student has not set up their name in profile, display their studentID, else display their name
        echo ($ongoingStudent["name"] == "") ?  $ongoingStudentID  :  $ongoingStudent["name"];
        echo '</div></a></td>
        <td>
            <div class="d-flex w-40">';
        echo ($ongoingStudent["working_title"] == "") ? 'No working title yet' : $ongoingStudent["working_title"];
        echo
        '</div>
        </td>
        <td>
            <a href="student-tasks.php?student=' . $ongoingStudentID . '" type="button" class="btn btn-outline-primary"><i class="fa-solid fa-list-check"></i>&nbsp;View progress</a>
        </td>
    </tr>';
    }
} else {
    echo '<tr><td colspan="3">No ongoing students at the moment.</td></tr>';
}
