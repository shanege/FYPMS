<?php
require_once 'header.php';
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-50">
        <?php
        if ($userData['role'] == "supervisor") {
            $supervisorID = $userData['userID'];
            $supervisorDetails = getLecturer($con, $supervisorID);
            echo
            '<form id="editProfileForm" method="POST">
                <fieldset>
                    <legend>Edit profile details</legend>
                    <div id="nameGroup" class="mb-3 ">
                        <label for="name" class="form-label">Name<span class="text-danger">&#42;</span></label>
                        <input id="nameInput" type="text" name="name" class="form-control" value="' . $supervisorDetails['name'] . '">
                    </div>
                    <div id="emailGroup" class="mb-3">
                        <label for="email" class="form-label">Email<span class="text-danger">&#42;</span></label>
                        <input id="emailInput" type="email" name="email" class="form-control" value="' . $supervisorDetails['email'] . '">
                    </div>
                    <div id="researchAreasGroup" class="mb-3">
                        <label for="researchAreas" class="form-label">Research area(s)<span class="text-danger">&#42;</span></label>
                        <textarea id="researchAreasInput" name="researchAreas" class="form-control">' . $supervisorDetails['research_area'] . '</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="proposedTopics" class="form-label">Proposed topic(s)</label>
                        <textarea name="proposedTopics" class="form-control">' . $supervisorDetails['proposed_topics'] . '</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control">' . $supervisorDetails['description'] . '</textarea>
                    </div>
                    <div id="message" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                    <div class="d-flex justify-content-end">
                        <a href="profile.php?id=' . $userData['userID'] . '" type="button" class="btn btn-secondary mx-2">
                            Cancel
                        </a>
                        <input id="saveBtn" type="submit" class="btn btn-primary mx-2" value="Save changes">
                    </div>
                </fieldset>
            </form>';
        }
        ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editProfileForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#message").html("").removeClass("bg-success").addClass("bg-body");

            $.ajax({
                url: "includes/editprofile-inc.php",
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
                    console.log(response);

                    if (!response.success) {
                        if (response.errors?.name) {
                            $("#nameInput").addClass("is-invalid");
                            $("#nameGroup").append(
                                '<div class="invalid-feedback">' + response.errors.name + "</div>"
                            );
                        }

                        if (response.errors?.email) {
                            $("#emailInput").addClass("is-invalid");
                            $("#emailGroup").append(
                                '<div class="invalid-feedback">' + response.errors.email + "</div>"
                            );
                        }

                        if (response.errors?.researchAreas) {
                            $("#researchAreasInput").addClass("is-invalid");
                            $("#researchAreasGroup").append(
                                '<div class="invalid-feedback">' + response.errors.researchAreas + "</div>"
                            );
                        }

                        if (response.errors?.sql) {
                            $("#message").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        $("#message").html(response.message).removeClass("bg-body").addClass("bg-success");
                    }

                    $('#editProfileForm')[0].reset();
                    $('#saveBtn').attr('disabled', false);
                    $('#saveBtn').val('Save changes');
                }
            })
        });
    });
</script>
</body>

</html>