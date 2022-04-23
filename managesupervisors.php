<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-75">
        <div class="vstack gap-3">
            <div>
                <h3>Update supervisors details (Bulk)</h3>
                <div class="bg-light border p-4">
                    <form id="updateSupervisorsDetailsBulk" method="POST" enctype="multipart/form-data">
                        Select Excel file
                        <input type="file" name="supervisorFile">
                        <input type="submit" name="updateSupervisors" id="updateSupervisors" value="Import">
                    </form>
                    <span id="response"></span>
                </div>
            </div>
            <div>
                <h3>Update supervisors details (Single)</h3>
                <div class="bg-light border p-4">
                    <form id="updateSupervisorsDetailsSingleForm" method="POST">
                        <fieldset>
                            <div id="nameSelectGroup" class="mb-3">
                                <label for="supervisorID">Select supervisor</label>
                                <select id="selectSupervisor" name="supervisorID" class="form-select">
                                    <option disabled selected value> -- select an option -- </option>
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
                            <div id="message" class="rounded-3 mb-2 bg-light bg-opacity-75 p-2 text-white user-select-none">&nbsp;</div>
                            <div class="d-flex justify-content-end">
                                <input id="saveBtn" type="submit" class="btn btn-primary mx-2" value="Save changes">
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <!-- <div>
                <h3>Set supervisor quota</h3>
                <div class="bg-light border p-4">
                    <form id="supervisorQuota" method="POST">
                        <input type="number" name="supervisorQuota" min="1">
                        <input type="submit" name="updateSupervisors" id="updateSupervisors" class="btn btn-primary" value="Set">
                    </form>
                    <span id="response"></span>
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
        $('#updateSupervisorsDetailsBulk').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/supervisor_details-inc.php",
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
                    $('#response').html(data);
                    $('#updateSupervisorsDetailsBulk')[0].reset();
                    $('#updateSupervisors').attr('disabled', false);
                    $('#updateSupervisors').val('Import');
                }
            })
        });

        // get supervisors and add them as options to the select tag
        var supervisorOptions = <?php echo json_encode(getAllSupervisors($con)) ?>;
        for (var i = 0; i < Object.keys(supervisorOptions).length; i++) {
            $('#selectSupervisor').append(new Option(supervisorOptions[i]['name'], supervisorOptions[i]['supervisorID']));
        }

        // initialise the select2 selector
        $('#selectSupervisor').select2();

        $('#selectSupervisor').on('select2:select', function(event) {
            var selected = event.params.data;
            $.ajax({
                url: "includes/getsupervisor-inc.php",
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
            $("#message").html("").removeClass("bg-success").addClass("bg-light");

            $.ajax({
                url: "includes/supervisor_editprofile-inc.php",
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
                    console.log(data);
                    var response = JSON.parse(data);

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
                            $("#message").html(response.errors.sql).removeClass("bg-light").addClass("bg-danger");
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