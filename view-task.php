<?php
require_once 'header.php';

$task = getTask($con, $_GET['taskID']);

if ($task === false) {
    echo "This task does not exist.";
    exit;
} else if (($userData['role'] == "supervisor" && $userData['userID'] != $task['supervisorID']) ||
    ($userData['role'] == "student" && $userData['userID'] != $task['studentID'])
) {
    echo "This is not your task.";
    exit;
}
?>
<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3 w-75">
        <h1><?php echo $task['title']; ?></h1>
        <p class="fs-5 mt-4"><?php echo $task['description'] == "" ? "No description was given for this task" : $task['description']; ?></p>
        <h3 class="mt-4">Attached files:</h3>
        <p id="supervisorAttachedFiles">Loading files <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></p>
        <h3 class="mt-4">Submission details:</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle w-100">
                <colgroup>
                    <col span="1" style="width:20%;">
                    <col span="1" style="width:80%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td>Status</td>
                        <td><?php echo $task['status']; ?></td>
                    </tr>
                    <tr>
                        <td>Deadline</td>
                        <td>
                            <?php
                            $deadline = new DateTime($task['deadline_at'], new DateTimeZone('Asia/Kuala_Lumpur'));

                            // get the current DateTime
                            $now = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));

                            $diff = $deadline->diff($now);

                            if ($task['status'] == "Completed") {
                                if ($diff->days < 7) {
                                    if ($diff->d == 0) {
                                        if ($diff->h == 0) {
                                            if ($diff->m == 0) {
                                                $timeLeft = $diff->s;
                                            } else {
                                                $timeLeft = $diff->m . ' minute(s) ' . $diff->s . ' second(s)';
                                            }
                                        } else {
                                            $timeLeft = $diff->h . ' hour(s) ' . $diff->m . ' minute(s)';
                                        }
                                    } else {
                                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                                    }
                                } else {
                                    $timeLeft = $deadline->format('D, d F Y H:i A');
                                }

                                $statusIcon = '<span class="text-success"><i class="bi bi-check-circle text-success fs-4"></i> Completed</span>';
                            } else if ($deadline < $now) {
                                $timeLeft = $deadline->format('D, d F Y H:i A');

                                $statusIcon = '<span class="text-danger"><i class="bi bi-exclamation-circle fs-4"></i> Overdue</span>';
                            } else {
                                $diff = $deadline->diff($now);

                                if ($diff->days < 7) {
                                    if ($diff->d == 0) {
                                        if ($diff->h == 0) {
                                            if ($diff->m == 0) {
                                                $timeLeft = $diff->s;
                                            } else {
                                                $timeLeft = $diff->m . ' minute(s) ' . $diff->s . ' second(s)';
                                            }
                                        } else {
                                            $timeLeft = $diff->h . ' hour(s) ' . $diff->m . ' minute(s)';
                                        }
                                    } else {
                                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                                    }
                                } else {
                                    $timeLeft = $deadline->format('D, d F Y H:i A');
                                }

                                $statusIcon = '<span><i class="bi bi-clock fs-4"></i> Ongoing</span>';
                            }

                            echo $timeLeft;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Grade</td>
                        <td><?php echo $task['grade'] === "-1" ? "Submission not graded yet" : $task['grade']; ?></td>
                    </tr>
                    <tr>
                        <td>Submitted files</td>
                        <td id="studentSubmittedFiles">
                            Loading files <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i>
                        </td>
                    </tr>
                    <tr>
                        <td>Submitted text</td>
                        <td><?php echo $task['submission_text'] === "" ? "-" : $task['submission_text']; ?></td>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <td><?php echo $task['remarks'] === "" ? "-" : $task['remarks']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
        if ($task['status'] == "Ongoing" && $userData['role'] == "student") {
            echo '
            <button type="button" class="btn btn-primary mx-2 mb-3" data-bs-toggle="modal" data-bs-target="#addSubmissionModal">
                Submit work
            </button>';
        } else if ($task['status'] == "Completed" && $userData['role'] == "supervisor") {
            echo '
            <button type="button" class="btn btn-primary mx-2 mb-3" data-bs-toggle="modal" data-bs-target="#gradeWorkModal">
                Grade work
            </button>';
        }
        ?>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
    <?php
    if ($task['status'] == "Ongoing" && $userData['role'] == "student") {
        echo
        '<div class="modal fade" id="addSubmissionModal" tabindex="-1" aria-labelledby="addSubmissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubmissionModalLabel">Add submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="addSubmissionForm">
                        <div class="modal-body">
                            <div id="submissionFileGroup" class="mb-3">
                                <label for="submissionFile" class="col-form-label">Submit file:</label>
                                <input id="submissionFileInput" type="file" name="submissionFile" class="form-control">
                            </div>
                            <div id="submissionTextGroup" class="mb-3">
                                <label for="submissionText" class="col-form-label">Text:</label>
                                <textarea id="submissionTextInput" type="text" name="submissionText" class="form-control"></textarea>
                            </div>
                            <div id="message" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    } else if ($task['status'] == "Completed" && $userData['role'] == "supervisor") {
        echo
        '<div class="modal fade" id="gradeWorkModal" tabindex="-1" aria-labelledby="gradeWorkModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gradeWorkModalLabel">Grade this work</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="gradeWorkForm">
                        <div class="modal-body">
                            <div id="gradeGroup" class="mb-3">
                                <label for="gradeFile" class="col-form-label">Grade:</label>
                                <input id="gradeInput" type="number" name="grade" class="form-control" min="1" max="100">
                            </div>
                            <div id="remarksGroup" class="mb-3">
                                <label for="remarks" class="col-form-label">Remarks:</label>
                                <textarea id="remarksInput" type="text" name="remarks" class="form-control"></textarea>
                            </div>
                            <div id="message" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="gradeBtn" type="submit" class="btn btn-primary">Grade</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    }
    ?>
</div>
<script>
    $(document).ready(function() {
        $("#supervisorAttachedFiles").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['supervisor_upload_path'] == "" ? "" : $task['supervisor_upload_path']; ?>"
        });

        $("#studentSubmittedFiles").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['student_submit_path'] == "" ? "" : $task['student_submit_path']; ?>"
        });

        $('#addSubmissionForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#message").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);
            formData.append('taskID', "<?php echo $_GET['taskID'] ?>");

            $.ajax({
                url: "includes/add-submission-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#addBtn').attr('disabled', 'disabled');
                    $('#addBtn').val('Submitting...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.submission) {
                            $("#submissionFileInput").addClass("is-invalid");
                            $("#submissionFileGroup").append(
                                '<div class="invalid-feedback">' + response.errors.submission + "</div>"
                            );

                            $("#submissionTextInput").addClass("is-invalid");
                            $("#submissionTextGroup").append(
                                '<div class="invalid-feedback">' + response.errors.submission + "</div>"
                            );
                        }

                        if (response.errors.task) {
                            $("#message").html(response.errors.task).removeClass("bg-body").addClass("bg-danger");
                        }

                        if (response.errors.sql) {
                            $("#message").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // resets the forms inputs
                        $('#addSubmissionForm')[0].reset();

                        var toast = $(createToast(response.message));

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        $(".toast-container").append(toast);

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();

                        var bsModal = bootstrap.Modal.getInstance($('#addSubmissionModal'));
                        bsModal.hide();

                        location.reload();
                    }
                    $('#submitBtn').attr('disabled', false);
                    $('#submitBtn').val('Add task');
                }
            })
        });

        $('#gradeWorkForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#message").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);
            formData.append('taskID', "<?php echo $_GET['taskID'] ?>");

            $.ajax({
                url: "includes/grade-work-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#gradeBtn').attr('disabled', 'disabled');
                    $('#gradeBtn').val('Adding grade...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.grade) {
                            $("#gradeInput").addClass("is-invalid");
                            $("#gradeGroup").append(
                                '<div class="invalid-feedback">' + response.errors.grade + "</div>"
                            );
                        }
                    } else {
                        // resets the forms inputs
                        $('#gradeWorkForm')[0].reset();

                        var toast = $(createToast(response.message));

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        $(".toast-container").append(toast);

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();

                        var bsModal = bootstrap.Modal.getInstance($('#gradeWorkModal'));
                        bsModal.hide();

                        location.reload();
                    }
                    $('#gradeBtn').attr('disabled', false);
                    $('#gradeBtn').val('Grade');
                }
            })
        });
    });

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