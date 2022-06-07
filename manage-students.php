<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-75 my-5">
        <div class="vstack gap-5">
            <div>
                <h3>
                    Update student details (Bulk)
                    <button type="button" class="btn btn-primary" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="
                    <p class='text-start'>Columns should have these columns in this order: Student ID, Name, Intake<br/><br/>
                    Settings in the sheet such as 'Wrap Text' will cause the Excel reader to think an empty cell is not empty, 
                    use 'Clear All' under the Editing tab in Excel to be safe</p>">
                        ?
                    </button>
                </h3>
                <div class="bg-light border p-4">
                    <form id="updateStudentsDetailsBulk" method="POST" enctype="multipart/form-data">
                        <label for="studentDetailsFile" class="form-label">Select Excel file</label>
                        <input type="file" name="studentDetailsFile" class="form-control mb-3">
                        <input type="submit" name="updateStudents" id="updateStudents" class="btn btn-primary" value="Import">
                    </form>
                    <div id="updateStudentsDetailsBulkError" class="rounded-3 mb-2 bg-light bg-opacity-75 p-2 user-select-none">&nbsp;</div>
                </div>
            </div>
            <!-- <div>
                <h3>Update Students details (Single)</h3>
                <div class="bg-light border p-4">
                    <form id="updateStudentsDetailsSingleForm" method="POST">
                        <fieldset>
                            <div id="nameSelectGroup" class="mb-3">
                                <label for="studentID" class="mb-2">Student</label>
                                <select id="selectStudent" name="studentID" class="form-select">
                                    <option disabled selected value> -- select a student -- </option>
                                </select>
                            </div>
                            <div id="nameGroup" class="mb-3 ">
                                <label for="name" class="form-label">Name<span class="text-danger">&#42;</span></label>
                                <input id="nameInput" type="text" name="name" class="form-control">
                            </div>
                            <div id="emailGroup" class="mb-3">
                                <label for="email" class="form-label">Email<span class="text-danger">&#42;</span></label>
                                <input id="emailInput" type="email" name="email" class="form-control">
                            </div>
                            <div id="researchAreasGroup" class="mb-3">
                                <label for="researchAreas" class="form-label">Research area(s)<span class="text-danger">&#42;</span></label>
                                <textarea id="researchAreasInput" name="researchAreas" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="proposedTopics" class="form-label">Proposed topic(s)</label>
                                <textarea id="proposedTopicsInput" name="proposedTopics" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="descriptionInput" name="description" class="form-control"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input id="saveBtn" type="submit" class="btn btn-primary mx-2" value="Save changes">
                            </div>
                        </fieldset>
                    </form>
                    <div id="updateStudentsDetailsSingleError" class="rounded-3 mb-2 bg-light bg-opacity-75 p-2 text-white user-select-none">&nbsp;</div>
                </div>
            </div> -->
        </div>
    </div>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11"></div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

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
    });
</script>