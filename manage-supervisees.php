<?php
require_once 'header.php';
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
}

?>
<nav>
    <div class="nav nav-tabs mt-3" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">Ongoing</button>
        <button class="nav-link" id="nav-requests-tab" data-bs-toggle="tab" data-bs-target="#nav-requests" type="button" role="tab" aria-controls="nav-requests" aria-selected="false">Requests</button>
        <button class="nav-link" id="nav-completed-tab" data-bs-toggle="tab" data-bs-target="#nav-completed" type="button" role="tab" aria-controls="nav-completed" aria-selected="false">Completed</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-ongoing" role="tabpanel" aria-labelledby="nav-ongoing-tab">
        <div id="ongoing-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Ongoing students under your supervision</h1>
                <div class="mb-5 fst-italic">Click on student names to view their profile or on the view progress button to view their tasks.</div>
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
                            require_once 'ongoing-students.php';
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
                <h1 class="mt-5 mb-1 fw-bold">Students requesting you as their supervisor</h1>
                <div class="mb-5 fst-italic">Click on student names to view their profile.</div>
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
                            require_once 'students-requests.php';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-completed" role="tabpanel" aria-labelledby="nav-completed-tab">
        <div id="completed-content" class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <h1 class="mt-5 mb-1 fw-bold">Students who completed their FYP under you</h1>
                <div class="mb-5 fst-italic">Click on student names to view their profile.</div>
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
                        <tbody id="completedTableBody">
                            <?php
                            require_once 'completed-students.php';
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
            $("#ongoingTableBody").load("ongoing-students.php");
        });

        $("#nav-requests-tab").click(function() {
            $("#requestsTableBody").load("students-requests.php");
        });

        $("#nav-completed-tab").click(function() {
            $("#completedTableBody").load("students-completed.php");
        });

        $(document).on('click', ".btn-success", function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");

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