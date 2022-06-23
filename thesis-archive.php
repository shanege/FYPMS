<?php
require_once 'header.php';
if ($userData["role"] == "coordinator") {
    exit("You are not allowed to access this page");
}
?>
<div id="content" class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <?php
        if ($userData['role'] == "supervisor") {
            echo
            '<h1 class="mt-5 mb-1 fw-bold">Manage your students theses</h1>
            <div class="fst-italic">Click on a link to download. Remove button will only appear for theses you have uploaded.</div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#archiveModal">
                    Archive a thesis
                </button>
            </div>';
        } else if ($userData['role'] == "student") {
            echo
            '<h1 class="mt-5 mb-1 fw-bold">View students theses</h1>
            <div class="mb-5 fst-italic">Click on a link to download.</div>';
        }
        ?>
        <div class="table-responsive my-3">
            <table class="table table-striped align-middle" style="width:80rem;">
                <colgroup>
                    <col span="1" style="width:5%;">
                    <col span="1" style="width:60%;">
                    <col span="1" style="width:15%;">
                    <col span="1" style="width:20%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $thesesDetails = getThesisArchive($con);
                    if (!empty($thesesDetails)) {
                        $i = 0;

                        foreach ($thesesDetails as $thesisDetails) {
                            echo
                            '<tr id=' . $thesisDetails['studentID'] . '>
                                <td>' . ++$i . '</td>
                                <td class="title">
                                    Loading <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i>
                                </td>
                                <td>';
                            echo $thesisDetails['name'] == "" ? $thesisDetails['studentID'] : $thesisDetails['name'];
                            echo '
                                </td>';
                            if ($thesisDetails['supervisorID'] == $userData['userID']) {
                                echo '<td>
                                    <div class="d-flex justify-content-evenly">
                                        <button type="button" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Remove</button>
                                    </div>
                                </td>';
                            } else {
                                echo '<td></td>';
                            }
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No archived thesis yet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    if ($userData['role'] == "supervisor") {
        $completedStudentsIDs = getAllStudentIDsForSupervisorByStatus($con, $_SESSION['userID'], "Completed");

        echo '<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="archiveModalLabel">Archive thesis</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="archiveForm">
                        <div class="modal-body">
                            <div id="studentGroup" class="col">
                                <label for="student" class="mb-2">Student:<span class="text-danger ms-2">&#42;Only students who completed their FYP under your supervision will show up here.</span></label>
                                <select id="studentInput" name="student" class="form-select mb-3" aria-label="Select student">
                                    <option disabled selected value> -- select a student -- </option>';

        if (!empty($completedStudentsIDs)) {
            foreach ($completedStudentsIDs as $completedStudentID) {
                $completedStudent = getStudent($con, $completedStudentID['studentID']);

                echo '<option value="' . $completedStudentID['studentID'] . '">';
                echo $completedStudent['name'] == "" ? $completedStudentsID : $completedStudent['name'];
                echo '</option>';
            }
        }
        echo '
                                </select>
                            </div>
                            <div id="archiveFileGroup" class="mb-3">
                                <label for="archiveFile" class="col-form-label">Thesis file:
                                    <div class="text-danger ms-2">&#42;This will replace the previous file if there was one. Please name the file the same as the title of the thesis as that is the name that will be shown</div>
                                </label>
                                <input id="archiveFileInput" type="file" name="archiveFile" class="form-control">
                            </div>
                            <div id="archiveMessage" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                        </div>
                        <div class="modal-footer">
                            <button id="archiveBtn" type="submit" class="btn btn-primary">Archive</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>';
    }
    ?>
    <div class="position-fixed bottom-0 end-0 p-3 toast-container" style="z-index: 11">
        <?php
        if (isset($_POST['proposeTopicResult']) && $_POST['proposeTopicResult'] == "success") {
            echo '
            <div id="proposeTopicToast" class="toast align-items-center text-white bg-success bg-opacity-75 border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                    Success! Topic added
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
        $(".title").each(function() {
            $(this).load("includes/display-files-inc.php", {
                folder: "Thesis archive/" + $(this).closest("tr").prop("id") + "/",
                style: "normal"
            });
        });

        $('#archiveForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $(".form-select").removeClass("is-invalid");
            $("#archiveMessage").html("&nbsp;").removeClass("bg-success").addClass("bg-body");

            var formData = new FormData(this);

            $.ajax({
                url: "includes/archive-inc.php",
                method: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#archiveBtn').attr('disabled', 'disabled');
                    $('#archiveBtn').val('Archiving...');
                },
                success: function(data) {
                    console.log(data);
                    var response = JSON.parse(data);

                    if (!response.success) {
                        if (response.errors.student) {
                            $("#studentInput").addClass("is-invalid");
                            $("#studentGroup").append(
                                '<div class="invalid-feedback">' + response.errors.student + "</div>"
                            );
                        }

                        if (response.errors.archiveFile) {
                            $("#archiveFileInput").addClass("is-invalid");
                            $("#archiveFileGroup").append(
                                '<div class="invalid-feedback">' + response.errors.archiveFile + "</div>"
                            );
                        }

                        if (response.errors.sql) {
                            $("#archiveMessage").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // resets the forms inputs
                        $('#archiveForm')[0].reset();

                        var toast = $(createToast(response.message));

                        toast.on('hidden.bs.toast', function() {
                            $(this).remove();
                        });

                        $(".toast-container").append(toast);

                        // create bootstrap toast and show it
                        var bsToast = new bootstrap.Toast(toast);
                        bsToast.show();

                        var bsModal = bootstrap.Modal.getInstance($('#archiveModal'));
                        bsModal.hide();

                        location.reload();
                    }
                    $('#archiveBtn').attr('disabled', false);
                    $('#archiveBtn').val('Archvie');
                }
            });
        });

        $(document).on('click', ".btn-danger", function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");

            var confirmModal = $(createModal("Before you proceed!", "Remove this thesis from archive?"));

            // add confirmation button to the modal
            confirmModal.find(".modal-content").append(
                `<div class="modal-footer">
                <button type="button" class="btn btn-primary confirm-button">Remove</button>
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
                    url: "includes/remove-thesis-inc.php",
                    method: "POST",
                    data: {
                        studentID: studentID,
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
                            if (response.errors.sql) {
                                errorMessage = errorMessage + response.errors.sql + "<br/>";
                            }

                            var errorModal = $(createModal("Something went wrong", errorMessage));

                            // add a listener that forces a reload on the page when the modal is closed
                            errorModal.on('hidden.bs.modal', function() {
                                window.location.reload();
                            });

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

        var proposeTopicToast = document.getElementById('proposeTopicToast');
        if (proposeTopicToast) {
            var toast = new bootstrap.Toast(proposeTopicToast);

            toast.show();

            proposeTopicToast.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    })
</script>
</body>

</html>