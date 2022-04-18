<?php
require_once 'header.php';
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
}
$students = getAllStudentsForSupervisor($con, $userData['userID']);

$pendingStudentsIDs = [];
$ongoingStudentsIDs = [];
$completedStudentsIDs = [];

if (!empty($students)) {
    foreach ($students as $key => $val) {
        if ($val['status'] == "Pending") {
            array_push($pendingStudentsIDs, $val['studentID']);
        } else if ($val['status'] == "Ongoing") {
            array_push($ongoingStudentsIDs, $val['studentID']);
        } else if ($val['status'] == "Completed") {
            array_push($completedStudentsIDs, $val['studentID']);
        }
    }
}
?>

<nav>
    <div class="nav nav-tabs mt-3" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">ongoing</button>
        <button class="nav-link" id="nav-requests-tab" data-bs-toggle="tab" data-bs-target="#nav-requests" type="button" role="tab" aria-controls="nav-requests" aria-selected="false">requests</button>
        <button class="nav-link" id="nav-completed-tab" data-bs-toggle="tab" data-bs-target="#nav-completed" type="button" role="tab" aria-controls="nav-completed" aria-selected="false">completed</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-ongoing" role="tabpanel" aria-labelledby="nav-ongoing-tab">
    </div>
    <div class="tab-pane fade" id="nav-requests" role="tabpanel" aria-labelledby="nav-requests-tab">
        <div class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <?php
                require_once 'studentsrequests.php';
                ?>
            </div>
            <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
            </div>
        </div>
    </div>
</div>

<script></script>
</body>

</html>