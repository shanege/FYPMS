<?php
require_once 'header.php';
?>
<nav>
    <div class="nav nav-tabs mt-3" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">ongoing</button>
        <button class="nav-link" id="nav-requests-tab" data-bs-toggle="tab" data-bs-target="#nav-requests" type="button" role="tab" aria-controls="nav-requests" aria-selected="false">requests</button>
        <button class="nav-link" id="nav-none-tab" data-bs-toggle="tab" data-bs-target="#nav-none" type="button" role="tab" aria-controls="nav-none" aria-selected="false">none</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-ongoing" role="tabpanel" aria-labelledby="nav-ongoing-tab">
        <div id="ongoing-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:20%;">
                            <col span="1" style="width:60%;">
                            <col span="1" style="width:20%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Working title</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="ongoingTableBody">
                            <?php
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
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-requests" role="tabpanel" aria-labelledby="nav-requests-tab">
        <div id="requests-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:20%;">
                            <col span="1" style="width:60%;">
                            <col span="1" style="width:20%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Working title</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="requestsTableBody">
                            <?php
                            if (!empty($pendingStudentsIDs)) {
                                foreach ($pendingStudentsIDs as $pendingStudentID) {
                                    $pendingStudent = getStudent($con, $pendingStudentID);

                                    echo
                                    '<tr id="' . $pendingStudentID . '">
                                        <td>
                                            <div class="d-flex w-40"><a href="profile.php?id=' . $pendingStudentID . '">';

                                    // if the student has not set up their name in profile, display their studentID, else display their name
                                    echo ($pendingStudent["name"] == "") ?  $pendingStudentID  :  $pendingStudent["name"];
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
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-none" role="tabpanel" aria-labelledby="nav-none-tab">
        <div id="ongoing-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:20%;">
                            <col span="1" style="width:80%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Working title</th>
                            </tr>
                        </thead>
                        <tbody id="ongoingTableBody">
                            <?php
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
                                echo '<tr><td colspan="3">No students without supervisors.</td></tr>';
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>