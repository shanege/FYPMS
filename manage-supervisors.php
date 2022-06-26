<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-75">
        <h1 class="mt-5 mb-1 fw-bold">Manage supervisors</h1>
        <div class="mb-5 fst-italic">You can update supervisor details by bulk or individually and update supervisor quota for a semester.</div>
        <div class="vstack gap-5">
            <div>
                <h3>
                    Update supervisors details (Bulk)
                    <button type="button" class="btn btn-primary" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="
                    <p class='text-start'>Excel files should have these columns in this order: SupervisorID, Name, Research Area, Email<br/><br/>
                    Settings in the sheet such as 'Wrap Text' will cause the Excel reader to think an empty cell is not empty, 
                    use 'Clear All' under the Editing tab in Excel to be safe</p>">
                        ?
                    </button>
                </h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="updateSupervisorsDetailsBulkForm" enctype="multipart/form-data">
                        <label for="supervisorDetailsFile" class="form-label">Select Excel file</label>
                        <input type="file" name="supervisorDetailsFile" class="form-control mb-3">
                        <input type="submit" name="updateSupervisors" id="updateSupervisors" class="btn btn-primary" value="Import">
                    </form>
                    <div id="updateSupervisorsDetailsBulkError" class="rounded-3 mb-2 ecru bg-opacity-75 p-2 user-select-none text-danger">&nbsp;</div>
                </div>
            </div>
            <div>
                <h3>Update supervisors details (Single)</h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="updateSupervisorsDetailsSingleForm">
                        <fieldset>
                            <div id="nameSelectGroup" class="mb-3">
                                <label for="supervisorID" class="mb-2">Supervisor</label>
                                <select id="selectSupervisor" name="supervisorID" class="form-select">
                                    <option disabled selected value> -- select a supervisor -- </option>
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
                                <label for="description" class="form-label">Description</label>
                                <textarea id="descriptionInput" name="description" class="form-control"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input id="saveBtn" name="saveBtn" type="submit" class="btn btn-primary mx-2" value="Save changes">
                            </div>
                        </fieldset>
                    </form>
                    <div id="updateSupervisorsDetailsSingleError" class="rounded-3 mb-2 text-light bg-opacity-75 p-2 user-select-none">&nbsp;</div>
                </div>
            </div>
            <div class="mb-5">
                <h3>Set supervisor quota</h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="setSupervisorQuotaForm" method="POST">
                        <fieldset>
                            <div class="row g-3 mb-3">
                                <div id="semesterGroup" class="col">
                                    <label for="semester" class="mb-2">Semester</label>
                                    <select id="semesterInput" name="semester" class="form-select" aria-label="Select semester">
                                        <option disabled selected value> -- select a semester -- </option>
                                    </select>
                                </div>
                                <div id="quotaGroup" class="col">
                                    <label for="quota" class="mb-2">Quota</label>
                                    <input type="number" id="quotaInput" name="quota" class="form-control" min="1">
                                </div>
                            </div>
                        </fieldset>
                        <input type="submit" name="setQuotaBtn" id="setQuotaBtn" class="btn btn-primary px-3" value="Set">
                    </form>
                    <div id="setSupervisorQuotaError" class="rounded-3 mb-2 ecru bg-opacity-75 p-2 text-white user-select-none">&nbsp;</div>
                </div>
            </div>
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
        });

        $('#updateSupervisorsDetailsBulkForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/supervisor-details-bulk-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#updateSupervisors').attr('disabled', 'disabled');
                    $('#updateSupervisors').val('Importing...');
                },
                success: function(data) {
                    $('#updateSupervisorsDetailsBulkError').html(data);
                    $('#updateSupervisorsDetailsBulkForm')[0].reset();
                    $('#updateSupervisors').attr('disabled', false);
                    $('#updateSupervisors').val('Import');
                }
            })
        });

        // get supervisors and add them as options to the select tag
        var supervisorOptions = <?php echo json_encode(getAllSupervisors($con)) ?>;
        for (var i = 0; i < Object.keys(supervisorOptions).length; i++) {
            if (supervisorOptions[i]['name'] == "") {
                $('#selectSupervisor').append(new Option(supervisorOptions[i]['supervisorID'], supervisorOptions[i]['supervisorID']))
            } else {
                $('#selectSupervisor').append(new Option(supervisorOptions[i]['name'], supervisorOptions[i]['supervisorID']));
            }
        }

        // initialise the select2 selector
        $('#selectSupervisor').select2();

        $('#selectSupervisor').on('select2:select', function(event) {
            event.preventDefault();
            var selected = event.params.data;

            $.ajax({
                url: "includes/get-supervisor-inc.php",
                method: "POST",
                data: {
                    supervisorID: selected["id"]
                },
                cache: false,
                beforeSend: function() {
                    // set placeholders
                    $("#nameInput").attr("placeholder", "fetching...");
                    $("#emailInput").attr("placeholder", "fetching...");
                    $("#researchAreasInput").attr("placeholder", "fetching...");
                    $("#proposedTopicsInput").attr("placeholder", "fetching...");
                    $("#descriptionInput").attr("placeholder", "fetching...");
                },
                success: function(data) {
                    // remove placeholders 
                    $("#nameInput").removeAttr("placeholder");
                    $("#emailInput").removeAttr("placeholder");
                    $("#researchAreasInput").removeAttr("placeholder");
                    $("#proposedTopicsInput").removeAttr("placeholder");
                    $("#descriptionInput").removeAttr("placeholder");

                    var response = JSON.parse(data);
                    $("#nameInput").val(response.name);
                    $("#emailInput").val(response.email);
                    $("#researchAreasInput").val(response.research_areas);
                    $("#proposedTopicsInput").val(response.proposed_topics);
                    $("#descriptionInput").val(response.description);
                }
            });
        });

        $('#updateSupervisorsDetailsSingleForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#updateSupervisorsDetailsSingleError").html("").removeClass("bg-success").addClass("ecru");

            $.ajax({
                url: "includes/supervisor-edit-profile-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#saveBtn').attr('disabled', 'disabled');
                    $('#saveBtn').val('Saving...');
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    console.log(data);

                    if (!response.success) {
                        if (response.errors.name) {
                            $("#nameInput").addClass("is-invalid");
                            $("#nameGroup").append(
                                '<div class="invalid-feedback">' + response.errors.name + "</div>"
                            );
                        }

                        if (response.errors.email) {
                            $("#emailInput").addClass("is-invalid");
                            $("#emailGroup").append(
                                '<div class="invalid-feedback">' + response.errors.email + "</div>"
                            );
                        }

                        if (response.errors.researchAreas) {
                            $("#researchAreasInput").addClass("is-invalid");
                            $("#researchAreasGroup").append(
                                '<div class="invalid-feedback">' + response.errors.researchAreas + "</div>"
                            );
                        }

                        if (response.errors.sql) {
                            $("#updateSupervisorsDetailsSingleError").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        var toast = $(createToast(response.message));

                        $(".toast-container").append(toast);

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();
                    }

                    // resets the forms inputs
                    $('#updateSupervisorsDetailsSingleForm')[0].reset();
                    $('#saveBtn').attr('disabled', false);
                    $('#saveBtn').val('Save changes');
                }
            })
        });

        const now = new Date();
        var thisYear = now.getFullYear();
        var thisMonth = now.getMonth();

        // getMonth() returns month as value from 0 - 11
        // if within April and September, set semester as 4, else set as 9
        var semesterMonth = (thisMonth >= 3 && thisMonth < 8) ? 4 : 9;

        // adds options for semesters in the past 2 years
        for (var i = 0; i < 2; i++) {
            $('#semesterInput').append(
                new Option(
                    thisYear.toString() + "0" + semesterMonth.toString(),
                    thisYear.toString() + "0" + semesterMonth.toString()
                )
            );

            if (semesterMonth - 5 == 4) {
                semesterMonth -= 5;
                $('#semesterInput').append(
                    new Option(
                        thisYear.toString() + "0" + semesterMonth.toString(),
                        thisYear.toString() + "0" + semesterMonth.toString()
                    )
                );
            }

            thisYear -= 1;
            semesterMonth = 9;
        }

        $('#setSupervisorQuotaForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: "includes/set-quota-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#setQuotaBtn').attr('disabled', 'disabled');
                    $('#setQuotaBtn').val('Setting...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.semester) {
                            $("#semesterInput").addClass("is-invalid");
                            $("#semesterGroup").append(
                                '<div class="invalid-feedback">' + response.errors.semester + "</div>"
                            );
                        }

                        if (response.errors.quota) {
                            $("#quotaInput").addClass("is-invalid");
                            $("#quotaGroup").append(
                                '<div class="invalid-feedback">' + response.errors.quota + "</div>"
                            );
                        }

                        if (response.errors.sql) {
                            $("#setSupervisorQuotaError").html(response.errors.sql).removeClass("ecru").addClass("bg-danger");
                        }
                    } else {
                        var toast = $(createToast(response.message));

                        $(".toast-container").append(toast);

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();
                    }

                    $('#setSupervisorQuotaForm')[0].reset();
                    $('#setQuotaBtn').attr('disabled', false);
                    $('#setQuotaBtn').val('Set');
                }
            })
        });
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