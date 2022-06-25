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
        <h1 class="fw-bold"><?php echo $task['title']; ?></h1>
        <p class="fs-5 mt-4"><?php echo $task['description'] == "" ? "No description was given for this task" : $task['description']; ?></p>
        <h3 class="mt-4">Attached file:</h3>
        <p id="supervisorAttachedFile">Loading file <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></p>
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
                                            if ($diff->i == 0) {
                                                $timeLeft = $diff->s . ' second(s)';
                                            } else {
                                                $timeLeft = $diff->i . ' minute(s) ' . $diff->s . ' second(s)';
                                            }
                                        } else {
                                            $timeLeft = $diff->h . ' hour(s) ' . $diff->i . ' minute(s)';
                                        }
                                    } else {
                                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                                    }
                                } else {
                                    $timeLeft = $deadline->format('D, d F Y, H:i A');
                                }

                                $statusIcon = '<span class="text-success"><i class="bi bi-check-circle text-success fs-4"></i> Completed</span>';
                            } else if ($deadline < $now) {
                                $timeLeft = $deadline->format('D, d F Y H:i A');

                                $statusIcon = '<span class="text-danger"><i class="bi bi-exclamation-circle fs-4"></i> Overdue</span>';
                            } else {
                                if ($diff->days < 7) {
                                    if ($diff->d == 0) {
                                        if ($diff->h == 0) {
                                            if ($diff->i == 0) {
                                                $timeLeft = $diff->s . ' second(s)';
                                            } else {
                                                $timeLeft = $diff->i . ' minute(s) ' . $diff->s . ' second(s)';
                                            }
                                        } else {
                                            $timeLeft = $diff->h . ' hour(s) ' . $diff->i . ' minute(s)';
                                        }
                                    } else {
                                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                                    }
                                } else {
                                    $timeLeft = $deadline->format('D, d F Y, H:i A');
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
                        <td>Submitted file</td>
                        <td id="studentSubmittedFile">
                            Loading file <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i>
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
        <div class="d-flex mb-3">
            <?php
            if ($userData['role'] == "supervisor") {
                echo '
                <button type="button" class="btn btn-secondary mx-2" data-bs-toggle="modal" data-bs-target="#editTaskModal">
                    Edit task details
                </button>';
            }
            if ($task['status'] == "Ongoing" && $userData['role'] == "student") {
                echo '
                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addSubmissionModal">
                    Submit work
                </button>';
            } else if ($task['status'] == "Completed" && $userData['role'] == "student") {
                echo '
                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addSubmissionModal">
                    Edit submission
                </button>';
            } else if ($task['status'] == "Completed" && $userData['role'] == "supervisor") {
                echo '
                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#gradeWorkModal">
                    Grade work
                </button>';
            }

            if ($userData['role'] == "supervisor") {
                echo '
                <button type="button" class="btn btn-danger ms-auto me-2" data-bs-toggle="modal" data-bs-target="#deleteTaskModal">
                    Delete task
                </button>';
            }
            ?>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
    <?php
    if ($userData['role'] == "supervisor") {
        echo
        '<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="editTaskForm">
                        <div class="modal-body">
                            <div id="titleGroup" class="mb-3">
                                <label for="title" class="col-form-label">Title:<span class="text-danger">&#42;</span></label>
                                <input id="titleInput" type="text" name="title" class="form-control" value="' . $task['title'] . '">
                            </div>
                            <div id="descriptionGroup" class="mb-3">
                                <label for="description" class="col-form-label">Description:</label>
                                <textarea id="descriptionInput" name="description" class="form-control">' . $task['description'] . '</textarea>
                            </div>
                            <div>Previously attached file: </div>
                            <div id="previousAttachedFile" class="mb-3">Loading file <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></div>
                            <div id="taskFileGroup" class="mb-3">
                                <label for="taskFile" class="col-form-label">Task file:<span class="text-danger ms-2">&#42;This will replace the previous file if there was one</span></label>
                                <input id="taskFileInput" type="file" name="taskFile" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="col-form-label">Deadline (MYT):<span class="text-danger">&#42;</span></label>
                                <div id="deadlineGroup" class="input-group">
                                    <input id="dateInput" name="date" type="date" class="form-control">
                                    <input id="timeInput" name="time" type="time" class="form-control">
                                </div>
                            </div>
                            <div id="editTaskMessage" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="editBtn" type="submit" class="btn btn-primary">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';

        echo '
        <div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteTaskModalLabel">Before you proceed!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Delete this task?
                        </div>
                        <div class="modal-footer">
                            <button id="deleteBtn" type="button" class="btn btn-primary">Delete task</button>
                        </div>
                    </div>
                </div>
            </div>';
    }
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
                            <div id="submissionMessage" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    } else if ($task['status'] == "Completed" && $userData['role'] == "student") {
        echo
        '<div class="modal fade" id="addSubmissionModal" tabindex="-1" aria-labelledby="addSubmissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubmissionModalLabel">Edit submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="addSubmissionForm">
                        <div class="modal-body">
                            <div>Previously submitted file: </div>
                            <div id="previousSubmittedFile" class="mb-3">Loading file <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></div>
                            <div id="submissionFileGroup" class="mb-3">
                                <label for="submissionFile" class="col-form-label">Submit file:<span class="text-danger ms-2">&#42;This will replace the previous file if there was one</span></label>
                                <input id="submissionFileInput" type="file" name="submissionFile" class="form-control">
                            </div>
                            <div id="submissionTextGroup" class="mb-3">
                                <label for="submissionText" class="col-form-label">Text:</label>
                                <textarea id="submissionTextInput" type="text" name="submissionText" class="form-control">' . $task['submission_text'] . '</textarea>
                            </div>
                            <div id="submissionMessage" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
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
                                <label for="gradeFile" class="col-form-label">Grade (1-100):<span class="text-danger ms-2">&#42;</span></label>
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
        $("#supervisorAttachedFile").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['supervisor_upload_path']; ?>",
            style: "normal"
        });

        $("#studentSubmittedFile").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['student_submit_path']; ?>",
            style: "normal"
        });

        $("#previousAttachedFile").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['supervisor_upload_path']; ?>",
            style: "normal"
        });

        $("#previousSubmittedFile").load("includes/display-files-inc.php", {
            folder: "<?php echo $task['student_submit_path']; ?>",
            style: "normal"
        });

        $('#addSubmissionForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#submissionMessage").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);
            formData.append('taskID', "<?php echo $_GET['taskID']; ?>");
            formData.append('previousFolder', "<?php echo $task['student_submit_path']; ?>");

            $.ajax({
                url: "includes/submission-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#submitBtn').attr('disabled', 'disabled');
                    $('#submitBtn').val('Submitting...');
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
                            $("#submissionMessage").html(response.errors.task).removeClass("bg-body").addClass("bg-danger");
                        }

                        if (response.errors.sql) {
                            $("#submissionMessage").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
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

        $('#editTaskForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#editTaskMessage").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);

            formData.append('studentID', "<?php echo $task['studentID'] ?>");
            formData.append('taskID', "<?php echo $_GET['taskID'] ?>");
            formData.append('previousFolder', "<?php echo $task['supervisor_upload_path']; ?>");

            $.ajax({
                url: "includes/edit-task-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#editBtn').attr('disabled', 'disabled');
                    $('#editBtn').val('Editing...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.title) {
                            $("#titleInput").addClass("is-invalid");
                            $("#titleGroup").append(
                                '<div class="invalid-feedback">' + response.errors.title + "</div>"
                            );
                        }

                        if (response.errors.deadline) {
                            $("#dateInput").addClass("is-invalid");
                            $("#timeInput").addClass("is-invalid");
                            $("#deadlineGroup").append(
                                '<div class="invalid-feedback">' + response.errors.deadline + "</div>"
                            );
                        }

                        if (response.errors.taskFiles) {
                            $("#taskFilesInput").addClass("is-invalid");
                            $("#taskFilesGroup").append(
                                '<div class="invalid-feedback">' + response.errors.taskFiles + "</div>"
                            );
                        }

                        if (response.errors.task) {
                            $("#editTaskMessage").html(response.errors.task).removeClass("bg-body").addClass("bg-danger");
                        }

                        if (response.errors.student) {
                            $("#editTaskMessage").html(response.errors.student).removeClass("bg-body").addClass("bg-danger");
                        }

                        if (response.errors.sql) {
                            $("#editTaskMessage").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // resets the forms inputs
                        $('#editTaskForm')[0].reset();

                        var toast = $(createToast(response.message));

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        $(".toast-container").append(toast);

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();

                        var bsModal = bootstrap.Modal.getInstance($('#editTaskModal'));
                        bsModal.hide();

                        location.reload();
                    }
                    $('#editBtn').attr('disabled', false);
                    $('#editBtn').val('Edit');
                }
            })
        });

        $('#gradeWorkForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#gradeWorkMessage").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

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

                        if (response.errors.sql) {
                            $("#gradeWorkMessage").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
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

        $(document).on('click', "#deleteBtn", function(event) {
            event.preventDefault();

            $.ajax({
                url: "includes/delete-task-inc.php",
                method: "POST",
                data: {
                    taskID: "<?php echo $_GET['taskID']; ?>",
                    supervisorUploadPath: "<?php echo $task['supervisor_upload_path']; ?>",
                    studentSubmitPath: "<?php echo $task['student_submit_path']; ?>"
                },
                cache: false,
                beforeSend: function() {
                    $('#deleteBtn').attr('disabled', 'disabled');
                    $('#deleteBtn').val('Deleting...');
                },
                success: function(data) {
                    console.log(data);
                    var response = JSON.parse(data);

                    if (!response.success) {
                        // force page reload after user closes alert dialog box
                        if (!alert("Could not delete task due to an error")) {
                            window.location.reload();
                        }
                    } else {
                        window.location.replace("student-tasks.php?student=<?php echo $task['studentID']; ?>");
                    }
                    $('#deleteBtn').attr('disabled', false);
                    $('#deleteBtn').val('Delete task');
                }
            });
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