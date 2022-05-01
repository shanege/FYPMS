<?php
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
} ?>
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
                <tbody>
                    <?php

                    echo
                    '';

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

                    echo '';
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
</div>
<script>
    $(document).ready(function() {
        $(".btn-success").click(function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("tbody tr").index(row);

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

        $(".btn-danger").click(function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("tbody tr").index(row);

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