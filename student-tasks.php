<?php
require_once 'header.php';
?>
<div class="position-relative">
    <div id="tasks" class="position-absolute top-0 start-50 translate-middle-x my-3 w-75">
        <?php
        if ($userData['role'] == "supervisor") {
            // returns false if student does not have a supervisor
            $supervisor = requestExists($con, $_GET['student']);

            if ($supervisor === false || $supervisor['supervisorID'] != $userData['userID']) {
                echo "This is not your student";
                exit;
            }
        }

        require_once 'display-tasks.php';
        ?>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
    <?php
    if ($userData['role'] == "supervisor") {
        echo
        '<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add task for this student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="addTaskForm">
                        <div class="modal-body">
                            <div id="titleGroup" class="mb-3">
                                <label for="title" class="col-form-label">Title:<span class="text-danger">&#42;</span></label>
                                <input id="titleInput" type="text" name="title" class="form-control">
                            </div>
                            <div id="descriptionGroup" class="mb-3">
                                <label for="description" class="col-form-label">Description:</label>
                                <textarea id="descriptionInput" name="description" class="form-control"></textarea>
                            </div>
                            <div id="taskFileGroup" class="mb-3">
                                <label for="taskFile" class="col-form-label">Task file:</label>
                                <input id="taskFileInput" type="file" name="taskFile" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="col-form-label">Deadline (MYT):<span class="text-danger">&#42;</span></label>
                                <div id="deadlineGroup" class="input-group">
                                    <input id="dateInput" name="date" type="date" class="form-control">
                                    <input id="timeInput" name="time" type="time" class="form-control">
                                </div>
                            </div>
                            <div id="message" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="addBtn" type="submit" class="btn btn-primary">Add task</button>
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
        $('#addTaskForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#message").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);

            formData.append('studentID', "<?php echo $_GET['student'] ?>");

            $.ajax({
                url: "includes/add-task-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#addBtn').attr('disabled', 'disabled');
                    $('#addBtn').val('Adding...');
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

                        if (response.errors.student) {
                            $("#message").html(response.errors.student).removeClass("bg-body").addClass("bg-danger");
                        }

                        if (response.errors.sql) {
                            $("#message").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // resets the forms inputs
                        $('#addTaskForm')[0].reset();

                        var toast = $(createToast(response.message));

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        $(".toast-container").append(toast);

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();

                        var bsModal = bootstrap.Modal.getInstance($('#addTaskModal'));
                        bsModal.hide();

                        $('#tasks').load('display-tasks.php?student=<?php echo $_GET['student'] ?>');
                    }
                    $('#addBtn').attr('disabled', false);
                    $('#addBtn').val('Add task');
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
</body>

</html>