<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-75">
        <h1 class="mt-5 mb-1 fw-bold">Manage students</h1>
        <div class="mb-5 fst-italic">You can update student details by bulk or assign a supervisor to a student who does not have one.</div>
        <div class="vstack gap-5">
            <div>
                <h3>
                    Update student details (Bulk)
                    <button type="button" class="btn btn-primary" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="
                    <p class='text-start'>Excel files should have these columns in this order: Student ID, Name, Intake<br/><br/>
                    Settings in the sheet such as 'Wrap Text' will cause the Excel reader to think an empty cell is not empty, 
                    use 'Clear All' under the Editing tab in Excel to be safe</p>">
                        ?
                    </button>
                </h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="updateStudentsDetailsBulk" method="POST" enctype="multipart/form-data">
                        <label for="studentDetailsFile" class="form-label">Select Excel file</label>
                        <input type="file" name="studentDetailsFile" class="form-control mb-3">
                        <input type="submit" name="updateStudents" id="updateStudents" class="btn btn-primary" value="Import">
                    </form>
                    <div id="updateStudentsDetailsBulkError" class="rounded-3 mb-2 ecru bg-opacity-75 p-2 user-select-none text-danger">&nbsp;</div>
                </div>
            </div>
            <div class="mb-5">
                <h3>Assign supervisor</h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="assignSupervisorForm" method="POST">
                        <fieldset>
                            <div class="row g-3 mb-3">
                                <div id="studentIDGroup" class="col">
                                    <label for="studentID" class="mb-2">Student ID</label>
                                    <select id="studentIDInput" name="studentID" class="form-select m-0" aria-label="Select Student ID">
                                        <option disabled selected value> -- select a Student ID -- </option>
                                    </select>
                                </div>
                                <div id="supervisorIDGroup" class="col">
                                    <label for="supervisorID" class="mb-2">Supervisor ID</label>
                                    <select id="supervisorIDInput" name="supervisorID" class="form-select m-0" aria-label="Select Supervisor ID">
                                        <option disabled selected value> -- select a supervisor -- </option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <input type="submit" id="assignBtn" name="assignBtn" class="btn btn-primary px-3 mb-3" value="Assign">
                    </form>
                    <div id="assignSupervisorError" class="rounded-3 mb-2 ecru bg-opacity-75 p-2 user-select-none text-white">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11">
        <?php
        if (isset($_POST['assignResult']) && $_POST['assignResult'] == "success") {
            echo '
        <div id="assignSuccessToast" class="toast align-items-center text-white bg-success bg-opacity-75 border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                Success!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>';
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $('#updateStudentsDetailsBulk').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/student-details-bulk-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#updateStudents').attr('disabled', 'disabled');
                    $('#updateStudents').val('Importing...');
                },
                success: function(data) {
                    $('#updateStudentsDetailsBulkError').html(data);
                    $('#updateStudentsDetailsBulk')[0].reset();
                    $('#updateStudents').attr('disabled', false);
                    $('#updateStudents').val('Import');

                    var toast = $(createToast("Student details imported"));

                    $(".toast-container").append(toast);

                    toast.on('hidden.bs.toast', function() {
                        $(this).remove();
                    });

                    // create bootstrap toast and show it
                    var bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                }
            })
        });

        var studentOptions = <?php echo json_encode(getStudentsWithoutSupervisor($con)) ?>;
        for (var i = 0; i < Object.keys(studentOptions).length; i++) {
            $('#studentIDInput').append(new Option(studentOptions[i]['studentID'], studentOptions[i]['studentID']));
        }

        // initialise the select2 selector
        $('#studentIDInput').select2();

        // get supervisors and add them as options to the select tag
        var supervisorOptions = <?php echo json_encode(getAllSupervisors($con)) ?>;
        for (var i = 0; i < Object.keys(supervisorOptions).length; i++) {
            $('#supervisorIDInput').append(new Option(supervisorOptions[i]['name'], supervisorOptions[i]['supervisorID']));
        }

        // initialise the select2 selector
        $('#supervisorIDInput').select2();

        $('#assignSupervisorForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-select").removeClass("is-invalid");
            $("#assignSupervisorError").html("&nbsp;").removeClass("bg-danger").addClass("ecru");

            var formData = new FormData(this);
            formData.append("assign", "true");

            $.ajax({
                url: "includes/assign-pair-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#assignBtn').attr('disabled', 'disabled');
                    $('#assignBtn').val('Assigning...');
                },
                success: function(data) {
                    console.log(data);
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.studentID) {
                            // adding style display block as the select2 selector is somehow setting elements below it display none by default
                            $("#studentIDGroup").append(
                                '<div class="invalid-feedback" style="display:block">' + response.errors.studentID + "</div>"
                            );
                        }

                        if (response.errors.supervisorID) {
                            // adding style display block as the select2 selector is somehow setting elements below it display none by default
                            $("#supervisorIDGroup").append(
                                '<div class="invalid-feedback" style="display:block">' + response.errors.supervisorID + "</div>"
                            );
                        }

                        if (response.errors.sql) {
                            $("#assignSupervisorError").html(response.errors.sql).removeClass("ecru").addClass("bg-danger");
                        }
                    } else {
                        // make a hidden form to send POST variable to itself that the assignment was a success
                        var form = $(document.createElement('form'));
                        $(form).attr("action", "");
                        $(form).attr("method", "POST");
                        $(form).css("display", "none");

                        var assignResult = $("<input>")
                            .attr("type", "text")
                            .attr("name", "assignResult")
                            .val("success");
                        $(form).append($(assignResult));

                        form.appendTo(document.body);
                        $(form).submit();
                    }
                    $('#assignBtn').attr('disabled', false);
                    $('#assignBtn').val('Assign');
                }
            })
        });

        var assignSuccessToast = document.getElementById('assignSuccessToast');
        if (assignSuccessToast) {
            var toast = new bootstrap.Toast(assignSuccessToast);

            toast.show();

            assignSuccessToast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    });

    function createToast(text) {
        return `<div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${text}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;
    }
</script>