<?php
require_once 'header.php';
?>
<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3 w-75">
        <?php
        if ($userData['role'] == "supervisor") {
            $supervisor = requestExists($con, $_GET['student']); // returns false if student does not have a supervisor
            if ($supervisor === false || $supervisor['supervisorID'] != $userData['userID']) {
                echo "This is not your student";
            } else if ($supervisor['supervisorID'] == $userData['userID']) {
                echo '
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary mx-2 mb-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        Request to be supervisor
                    </button>
                </div>';
                $tasks = getTasksForPair($con, $_GET['student'], $userData['userID']);
                echo '
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                </div>';
            }
        }
        ?>
    </div>
    <?php
    if ($supervisor['supervisorID'] == $userData['userID']) {
        echo
        '<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTaskModalLabel">Add task for this student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addTaskForm">
                        <div class="modal-body">
                            <div id="titleGroup" class="mb-3">
                                <label for="title" class="col-form-label">Title:<span class="text-danger">&#42;</span></label>
                                <input id="titleInput" type="text" name="title" class="form-control">
                            </div>
                            <div id="descriptionGroup" class="mb-3">
                                <label for="description" class="col-form-label">Description:</label>
                                <textarea id="descriptionInput" name="description" class="form-control"></textarea>
                            </div>
                            <div id="taskFilesGroup" class="mb-3">
                                <label for="taskFiles" class="col-form-label">Task files:</label>
                                <input id="taskFilesInput" type="file" name="taskFiles" class="form-control">
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
            $("#message").html("").removeClass("bg-success").addClass("bg-body");

            $.ajax({
                url: "includes/add-task-inc.php",
                method: "POST",
                data: new FormData(this),
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
                        if (response.errors.name) {
                            $("#nameInput").addClass("is-invalid");
                            $("#nameGroup").append(
                                '<div class="invalid-feedback">' + response.errors.name + "</div>"
                            );
                        }

                        if (response.errors.sql) {
                            $("#message").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // resets the forms inputs
                        $('#addTaskForm')[0].reset();
                        $('#addBtn').attr('disabled', false);
                        $('#addBtn').val('Add task');
                    }
                }
            })
        });
    });
</script>
</body>

</html>