<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>
<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-75">
        <h1 class="mt-5 mb-1 fw-bold">Add users</h1>
        <div class="mb-5 fst-italic">You can add users by bulk or individually.</div>
        <div class="vstack gap-5">
            <div>
                <h3>
                    Register users (Bulk)
                    <button type="button" class="btn btn-primary" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" title="
                    <p class='text-start'>Excel files should have these columns in this order: UserID, Password, Role<br/><br/>
                    Settings in the sheet such as 'Wrap Text' will cause the Excel reader to think an empty cell is not empty, 
                    use 'Clear All' under the Editing tab in Excel to be safe</p>">
                        ?
                    </button>
                </h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="registerUsersBulkForm" enctype="multipart/form-data">
                        <label for="registerUsersFile" class="form-label">Select Excel file</label>
                        <input type="file" name="registerUsersFile" class="form-control mb-3">
                        <input type="submit" name="registerUsers" id="registerUsers" class="btn btn-primary" value="Import">
                    </form>
                    <span class="text-danger" id="registerUsersBulkError"></span>
                </div>
            </div>
            <div class="mb-5">
                <h3>Register users (Single)</h3>
                <div class="ecru border p-4 rounded-3">
                    <form id="registerUsersSingleForm" method="POST">
                        <fieldset>
                            <div class="row g-3 mb-3">
                                <div id="userIDGroup" class="col">
                                    <label for="userID" class="mb-2">User ID</label>
                                    <input id="userIDInput" type="text" name="userID" class="form-control">
                                </div>
                                <div id="passwordGroup" class="col">
                                    <label for="password" class="mb-2">Password</label>
                                    <input id="passwordInput" type="password" name="password" class="form-control">
                                </div>
                                <div id="roleGroup" class="col">
                                    <label for="role" class="mb-2">Role</label>
                                    <select id="roleInput" name="role" class="form-select" aria-label="Select role">
                                        <option disabled selected value> -- select a role -- </option>
                                        <option value="student">Student</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="coordinator">Coordinator</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <input type="submit" id="registerBtn" class="btn btn-primary px-3" value="Register">
                    </form>
                    <div id="updateSupervisorsDetailsSingleError" class="rounded-3 mb-2 ecru bg-opacity-75 p-2 user-select-none">&nbsp;</div>
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

        $('#registerUsersBulkForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/bulk-register-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#registerUsers').attr('disabled', 'disabled');
                    $('#registerUsers').val('Importing...');
                },
                success: function(data) {
                    $('#registerUsersBulkError').html(data);
                    $('#registerUsersBulkForm')[0].reset();
                    $('#registerUsers').attr('disabled', false);
                    $('#registerUsers').val('Import');
                }
            })
        });

        $('#registerUsersSingleForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $(".form-select").removeClass("is-invalid");
            $("#updateSupervisorsDetailsSingleError").html("").removeClass("bg-success").addClass("ecru");

            $.ajax({
                url: "includes/single-register-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#registerBtn').attr('disabled', 'disabled');
                    $('#registerBtn').val('Registering...');
                },
                success: function(data) {
                    console.log(data);
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.userID) {
                            $("#userIDInput").addClass("is-invalid");
                            $("#userIDGroup").append(
                                '<div class="invalid-feedback">' + response.errors.userID + "</div>"
                            );
                        }

                        if (response.errors.password) {
                            $("#passwordInput").addClass("is-invalid");
                            $("#passwordGroup").append(
                                '<div class="invalid-feedback">' + response.errors.password + "</div>"
                            );
                        }

                        if (response.errors.role) {
                            $("#roleInput").addClass("is-invalid");
                            $("#roleGroup").append(
                                '<div class="invalid-feedback">' + response.errors.role + "</div>"
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

                        // resets the forms inputs
                        $('#registerUsersSingleForm')[0].reset();
                    }
                    $('#registerBtn').attr('disabled', false);
                    $('#registerBtn').val('Register');
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
</body>

</html>