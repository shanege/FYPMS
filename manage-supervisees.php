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
    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
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
                                echo '<tr><td colspan="3">No completed students at the moment.</td></tr>';
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#nav-ongoing-tab").click(function() {
            $("#ongoingTableBody").load("ongoing-students.php", {
                userID: "<?php echo $userData['userID'] ?>"
            });
        });

        $("#nav-requests-tab").click(function() {
            $("#requestsTableBody").load("students-requests.php", {
                userID: "<?php echo $userData['userID'] ?>"
            });
        });

        $(document).on('click', ".btn-success", function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("#requestsTableBody tr").index(row);

            var confirmModal = $(createModal("Before you proceed!", "Accept this student?"));

            // add confirmation button to the modal
            confirmModal.find(".modal-content").append(
                `<div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm-button">Accept</button>
                </div>`
            );

            // event listener to destroy the data on the DOM element once it is closed
            confirmModal.on('hidden.bs.modal', function() {
                $(this).remove();
            });

            // create bootstrap modal and show it
            var bsConfirmModal = new bootstrap.Modal(confirmModal);
            bsConfirmModal.show();

            confirmModal.find(".confirm-button").click(function(event) {
                $.ajax({
                    url: "includes/accept-student-inc.php",
                    method: "POST",
                    data: {
                        studentID: studentID,
                        rowNum: rowNum
                    },
                    cache: false,
                    beforeSend: function() {
                        thisBtn.attr('disabled', 'disabled');
                        thisBtn.siblings(".btn-danger").attr('disabled', 'disabled');
                    },
                    success: function(data) {
                        var response = JSON.parse(data);
                        var errorMessage = '';

                        if (!response.success) {
                            if (response.errors.empty) {
                                errorMessage = errorMessage + response.errors.empty + "<br/>";
                            }

                            if (response.errors.mismatch) {
                                errorMessage = errorMessage + response.errors.mismatch + "<br/>";
                            }

                            if (response.errors.sql) {
                                errorMessage = errorMessage + response.errors.sql + "<br/>";
                            }

                            var errorModal = $(createModal("Something went wrong", errorMessage));

                            // add a listener that forces a reload on the page when the modal is closed
                            errorModal.on('hidden.bs.modal', function() {
                                window.location.reload();
                            });

                            $("#requests-content").append(errorModal);

                            // create bootstrap modal and show it
                            var bsErrorModal = new bootstrap.Modal(errorModal);
                            bsErrorModal.show();
                        } else {
                            row.remove();
                            var toast = $(createToast(response.message));

                            toast.on('hidden.bs.toast', function() {
                                $(this).remove();
                            });

                            $(".toast-container").append(toast);

                            // create bootstrap toast and show it
                            var bsToast = new bootstrap.Toast(toast);
                            bsToast.show();
                        }
                    }
                });

                bsConfirmModal.hide();
            });

            $("#requests-content").append(confirmModal);
        });

        $(document).on('click', ".btn-danger", function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("#requestsTableBody tr").index(row);

            var confirmModal = $(createModal("Before you proceed!", "Reject this student?"));

            // add confirmation button to the modal
            confirmModal.find(".modal-content").append(
                `<div class="modal-footer">
                    <button type="button" class="btn btn-primary confirm-button">Reject</button>
                </div>`
            );

            // event listener to destroy the data on the DOM element once it is closed
            confirmModal.on('hidden.bs.modal', function() {
                $(this).remove();
            });

            // create bootstrap modal and show it
            var bsConfirmModal = new bootstrap.Modal(confirmModal);
            bsConfirmModal.show();

            confirmModal.find(".confirm-button").click(function(event) {
                $.ajax({
                    url: "includes/reject-student-inc.php",
                    method: "POST",
                    data: {
                        studentID: studentID,
                        rowNum: rowNum
                    },
                    cache: false,
                    beforeSend: function() {
                        thisBtn.attr('disabled', 'disabled');
                        thisBtn.siblings(".btn-success").attr('disabled', 'disabled');
                    },
                    success: function(data) {
                        var response = JSON.parse(data);
                        var errorMessage = '';

                        if (!response.success) {
                            if (response.errors.empty) {
                                errorMessage = errorMessage + response.errors.empty + "<br/>";
                            }

                            if (response.errors.mismatch) {
                                errorMessage = errorMessage + response.errors.mismatch + "<br/>";
                            }

                            if (response.errors.sql) {
                                errorMessage = errorMessage + response.errors.sql + "<br/>";
                            }

                            var errorModal = $(createModal("Something went wrong", errorMessage));

                            // add a listener that forces a reload on the page when the modal is closed
                            errorModal.on('hidden.bs.modal', function() {
                                window.location.reload();
                            });

                            $("#requests-content").append(errorModal);

                            // create bootstrap modal and show it
                            var bsErrorModal = new bootstrap.Modal(errorModal);
                            bsErrorModal.show();
                        } else {
                            row.remove();
                            var toast = $(createToast(response.message));

                            toast.on('hidden.bs.toast', function() {
                                $(this).remove();
                            });

                            $(".toast-container").append(toast);

                            // create bootstrap toast and show it
                            var bsToast = new bootstrap.Toast(toast);
                            bsToast.show();
                        }
                    }
                });

                bsConfirmModal.hide();
            });

            $("#requests-content").append(confirmModal);
        })
    });

    function createModal(title, text) {
        return `<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">${title}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ${text}
                            </div>
                        </div>
                    </div>
                </div>`;
    }

    function createToast(text) {
        return `<div class="toast align-items-center text-white bg-success bg-opacity-75 border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${text}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>`;
    }
</script>
</body>

</html>