<?php
require_once 'header.php';
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x">
        <?php
        $supervisorID = $_GET['id'];
        $supervisorDetails = getLecturer($con, $supervisorID);

        if (!$supervisorDetails) {
            echo 'This supervisor could not be found.';
        } else {
            echo '<div class="table-responsive">
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
                            </tbody></table></div>';
        }
        $supervisorRequest = requestExists($con, $userData["userID"]);

        if ($userData["role"] == "student") {
            if ($supervisorRequest == false) {
                echo
                '<div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestSupervisorModal">
                        Request to be supervisor
                    </button>
                </div>';
            } // button to trigger modal

            else if ($supervisorRequest["supervisorID"] == $supervisorID && $supervisorRequest["status"] == "Pending") {
                echo
                '<div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#cancelRequestModal">
                        Cancel request
                    </button>
                    <button type="button" class="btn btn-primary" disabled>
                        Request pending...
                    </button>
                </div>';
            }
        }
        ?>
    </div>
</div>
<?php
if ($userData["role"] == "student") {
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
    } else if ($supervisorRequest["supervisorID"] == $supervisorID && $supervisorRequest["status"] == "Pending") {
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
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#requestBtn').click(function(event) {

            event.preventDefault();
            $.ajax({
                url: "includes/request_supervisor-inc.php",
                method: "POST",
                data: {
                    requestingStudentID: "<?php echo $userData["userID"] ?>",
                    requestedSupervisorID: "<?php echo $supervisorID ?>"
                },
                cache: false,
                beforeSend: function() {
                    $('#requestBtn').attr('disabled', 'disabled');
                    $('#requestBtn').text('Sending request...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        // force page reload after user closes alert dialog box
                        if (!alert("Could not make request due to an error: " + response.error)) {
                            window.location.reload();
                        }
                    }
                    $('#requestBtn').removeClass('btn-primary').addClass('btn-secondary');
                    $('#requestBtn').text('Request sent!');
                }
            })
        });
        $('#cancelBtn').click(function(event) {

            // event.preventDefault();
            $.ajax({
                url: "includes/cancel_supervisor-inc.php",
                method: "POST",
                data: {
                    requestingStudentID: "<?php echo $userData["userID"] ?>",
                    requestedSupervisorID: "<?php echo $supervisorID ?>"
                },
                cache: false,
                beforeSend: function() {
                    $('#cancelBtn').attr('disabled', 'disabled');
                    $('#cancelBtn').text('Cancelling request...');
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    if (!response.success) {
                        // force page reload after user closes alert dialog box
                        if (!alert("Could not make request due to an error: " + response.error)) {
                            window.location.reload();
                        }
                    }
                    $('#cancelBtn').text('Done!');
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
</script>
</body>

</html>