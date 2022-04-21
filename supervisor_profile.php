<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <?php
        $supervisorDetails = getSupervisor($con, $id);

        if (!$supervisorDetails) {
            echo 'This supervisor could not be found.';
        } else {
            echo
            '<div class="table-responsive">
                <table class="table table-striped" style="width:50rem;">
                    <tbody>
                        <tr>
                            <th scope="row">Name</th>
                            <td>' . $supervisorDetails["name"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Research area(s)</th>
                            <td>' . $supervisorDetails["research_area"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>' . $supervisorDetails["email"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Proposed topic(s)</th>
                            <td>' . $supervisorDetails["proposed_topics"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Description</th>
                            <td>' . $supervisorDetails["description"] . '</td>
                        </tr>
                    </tbody>
                </table>
            </div>';
        }

        if ($userData['role'] == "student") {
            $supervisorRequest = requestExists($con, $userData['userID']);

            // if the student does not have an existing request button
            if ($supervisorRequest == false) {
                // create button to trigger request supervisor modal
                echo
                '<div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#requestSupervisorModal">
                        Request to be supervisor
                    </button>
                </div>';
            }
            // if the student has a request and it is for this supervisor
            else if ($supervisorRequest['supervisorID'] == $id && $supervisorRequest['status'] == "Pending") {
                // create button for triggering cancel request modal
                echo
                '<div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary mx-2" data-bs-toggle="modal" data-bs-target="#cancelRequestModal">
                        Cancel request
                    </button>
                    <button type="button" class="btn btn-primary mx-2" disabled>
                        Request pending...
                    </button>
                </div>';
            }
        } else if ($userData['role'] == "supervisor" && $userData['userID'] == $id) {
            echo
            '<div class="d-flex justify-content-end">
                <a href="editprofile.php" type="button" class="btn btn-primary mx-2">
                    Edit Profile
                </a>
            </div>';
        }
        ?>
    </div>
    <?php
    if ($userData['role'] == "student") {
        if ($supervisorRequest == false) {
            // modal for sending request
            echo
            '<div class="modal fade" id="requestSupervisorModal" tabindex="-1" aria-labelledby="requestSupervisorModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestSupervisorModalLabel">Before you proceed!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Please make sure you have contacted this supervisor (e.g., via email or Microsoft Teams) and have gotten their agreement to be your supervisor
                        </div>
                        <div class="modal-footer">
                            <button id="requestBtn" type="button" class="btn btn-primary" >Make request</button>
                        </div>
                    </div>
                </div>
            </div>';
        } else if ($supervisorRequest['supervisorID'] == $id && $supervisorRequest["status"] == "Pending") {
            // modal for cancelling request
            echo
            '<div class="modal fade" id="cancelRequestModal" tabindex="-1" aria-labelledby="cancelRequestModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelRequestModalLabel">Before you proceed!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to cancel your request?
                        </div>
                        <div class="modal-footer">
                            <button id="cancelBtn" type="button" class="btn btn-primary" >Cancel request</button>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }

    if (isset($_POST['editResult']) && $_POST['editResult'] == "success") {
        echo '
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="editSuccessToast" class="toast align-items-center text-white bg-success bg-opacity-75 border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                    Success! Changes saved
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>';
    }
    ?>
</div>
<script>
    $(document).ready(function() {
        $('#requestBtn').click(function(event) {
            var thisBtn = $(this);

            event.preventDefault();
            $.ajax({
                url: "includes/request_supervisor-inc.php",
                method: "POST",
                data: {
                    requestingStudentID: "<?php echo $_SESSION['userID'] ?>",
                    requestedSupervisorID: "<?php echo $id ?>"
                },
                cache: false,
                beforeSend: function() {
                    thisBtn.attr('disabled', 'disabled');
                    thisBtn.text('Sending request...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        // force page reload after user closes alert dialog box
                        if (!alert("Could not make request due to an error: " + response.errors)) {
                            window.location.reload();
                        }
                    }
                    thisBtn.removeClass('btn-primary').addClass('btn-secondary');
                    thisBtn.text('Request sent!');
                }
            })
        });
        $('#cancelBtn').click(function(event) {
            var thisBtn = $(this);

            event.preventDefault();
            $.ajax({
                url: "includes/cancel_supervisor-inc.php",
                method: "POST",
                data: {
                    requestingStudentID: "<?php echo $_SESSION['userID'] ?>",
                    requestedSupervisorID: "<?php echo $id ?>"
                },
                cache: false,
                beforeSend: function() {
                    thisBtn.attr('disabled', 'disabled');
                    thisBtn.text('Cancelling request...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        // force page reload after user closes alert dialog box
                        if (!alert("Could not make request due to an error: " + response.errors)) {
                            window.location.reload();
                        }
                    }
                    thisBtn.text('Done!');
                }
            })
        });
    });
    $(document).ajaxStop(function() {
        // check if DOM element exists 
        if (document.getElementById('requestSupervisorModal')) {
            var requestSupervisorModal = document.getElementById('requestSupervisorModal');

            // force page reload after closing the modal
            requestSupervisorModal.addEventListener('hidden.bs.modal', function(event) {
                window.location.reload();
            })
        }

        // check if DOM element exists 
        if (document.getElementById('cancelRequestModal')) {
            var cancelRequestModal = document.getElementById('cancelRequestModal');

            // force page reload after closing the modal
            cancelRequestModal.addEventListener('hidden.bs.modal', function(event) {
                window.location.reload();
            })
        }
    });

    var editSuccessToast = document.getElementById('editSuccessToast');
    if (editSuccessToast) {
        var toast = new bootstrap.Toast(editSuccessToast);

        toast.show();
    }
</script>
</body>

</html>