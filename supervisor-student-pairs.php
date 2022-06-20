<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>
<nav>
    <div class="nav nav-tabs mt-3" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">Ongoing</button>
        <button class="nav-link" id="nav-pending-tab" data-bs-toggle="tab" data-bs-target="#nav-pending" type="button" role="tab" aria-controls="nav-pending" aria-selected="false">Pending</button>
        <button class="nav-link" id="nav-completed-tab" data-bs-toggle="tab" data-bs-target="#nav-completed" type="button" role="tab" aria-controls="nav-completed" aria-selected="false">Completed</button>
        <button class="nav-link" id="nav-none-tab" data-bs-toggle="tab" data-bs-target="#nav-none" type="button" role="tab" aria-controls="nav-none" aria-selected="false">None</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-ongoing" role="tabpanel" aria-labelledby="nav-ongoing-tab">
        <div id="ongoing-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Ongoing student supervisor pairings</h1>
                <div class="mb-5 fst-italic">Click on any table heading to sort the table.</div>
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle sortable" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student ID</th>
                                <th scope="col">Supervisor ID</th>
                                <th scope="col">Starting Semester</th>
                            </tr>
                        </thead>
                        <tbody id="ongoingTableBody">
                            <?php
                            $ongoingPairs = getStudentSupervisorPairsByStatus($con, "Ongoing");

                            if (!empty($ongoingPairs)) {
                                foreach ($ongoingPairs as $ongoingPair) {
                                    echo
                                    '<tr>
                                        <td><a href="profile.php?id=' . $ongoingPair['studentID'] . '">' . $ongoingPair['studentID'] . '</a></td>
                                        <td><a href="profile.php?id=' . $ongoingPair['supervisorID'] . '">' . $ongoingPair['supervisorID'] . '</a></td>
                                        <td>' . $ongoingPair['starting_semester'] . '</td>
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
    <div class="tab-pane fade" id="nav-pending" role="tabpanel" aria-labelledby="nav-pending-tab">
        <div id="pending-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Pending student supervisor pairings</h1>
                <div class="mb-5 fst-italic">Click on any table heading to sort the table.</div>
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle sortable" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student ID</th>
                                <th scope="col">Supervisor ID</th>
                                <th scope="col">Starting Semester</th>
                            </tr>
                        </thead>
                        <tbody id="pendingTableBody">
                            <?php
                            $pendingPairs = getStudentSupervisorPairsByStatus($con, "Pending");

                            if (!empty($pendingPairs)) {
                                foreach ($pendingPairs as $pendingPair) {
                                    echo
                                    '<tr>
                                        <td><a href="profile.php?id=' . $pendingPair['studentID'] . '">' . $pendingPair['studentID'] . '</a></td>
                                        <td><a href="profile.php?id=' . $pendingPair['supervisorID'] . '">' . $pendingPair['supervisorID'] . '</a></td>
                                        <td>' . $pendingPair['starting_semester'] . '</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No pending students at the moment.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
        <div id="completed-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Completed student supervisor pairings</h1>
                <div class="mb-5 fst-italic">Click on any table heading to sort the table.</div>
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle sortable" style="width:80rem;">
                        <colgroup>
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                            <col span="1" style="width:33.3%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student ID</th>
                                <th scope="col">Supervisor ID</th>
                                <th scope="col">Starting Semester</th>
                            </tr>
                        </thead>
                        <tbody id="completedTableBody">
                            <?php
                            $completedPairs = getStudentSupervisorPairsByStatus($con, "Completed");

                            if (!empty($completedPairs)) {
                                foreach ($completedPairs as $completedPair) {
                                    echo
                                    '<tr>
                                        <td><a href="profile.php?id=' . $completedPair['studentID'] . '">' . $completedPair['studentID'] . '</a></td>
                                        <td><a href="profile.php?id=' . $completedPair['supervisorID'] . '">' . $completedPair['supervisorID'] . '</a></td>
                                        <td>' . $completedPair['starting_semester'] . '</td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No completed students at the moment.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-none" role="tabpanel" aria-labelledby="nav-none-tab">
        <div id="none-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Students without supervisor</h1>
                <div class="mb-5 fst-italic">Click on any table heading to sort the table. Go to Manage Users > Manage Students > Set Supervisor to assign a supervisor to these students.</div>
                <div class="table-responsive my-3">
                    <table class="table table-striped align-middle sortable">
                        <colgroup>
                            <col span="1" style="width:33.3%;">
                        </colgroup>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Student ID</th>
                            </tr>
                        </thead>
                        <tbody id="noneTableBody">
                            <?php
                            $studentsWithoutSupervisor = getStudentsWithoutSupervisor($con);

                            if (!empty($studentsWithoutSupervisor)) {
                                foreach ($studentsWithoutSupervisor as $studentWithoutSupervisor) {
                                    echo
                                    '<tr>
                                        <td><a href="profile.php?id=' . $studentWithoutSupervisor['studentID'] . '">' . $studentWithoutSupervisor['studentID'] . '</a></td>
                                    </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">No students without supervisor at the moment.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="includes/sorttable.js"></script>
</body>

</html>