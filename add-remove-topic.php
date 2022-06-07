<?php
require_once 'header.php';
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
}
?>
<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <div class="d-flex justify-content-end">
            <a href="propose-topic.php" type="button" class="btn btn-primary mx-2">Propose a topic</a>
        </div>
        <div class="table-responsive my-3">
            <table class="table table-striped align-middle" style="width:80rem;">
                <colgroup>
                    <col span="1" style="width:5%;">
                    <col span="1" style="width:75%;">
                    <col span="1" style="width:20%;">
                </colgroup>
                <thead class="table-light">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Proposed topic</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $proposedTopics = getProposedTopics($con, $userData['userID']);
                    if (!empty($proposedTopics)) {
                        $i = 0;

                        // a transform is applied to 2nd td so that the stretched link does not spread over it
                        foreach ($proposedTopics as $proposedTopic) {
                            echo
                            '<tr id="' . $proposedTopic['topicID'] . '" class="position-relative">
                                <td>' . ++$i . '</td>
                                <td style="transform: rotate(0);">
                                    <div class="d-flex text-break">
                                        <button type="button" class="stretched-link btn btn-outline-none shadow-none flex-grow-1 text-start" data-bs-toggle="collapse" data-bs-target="#topic' . $i . '" aria-expanded="false" aria-controls="topic' . $i . '">' . $proposedTopic['topic'] . '</button>
                                        <div class="align-self-center"><i class="fa-solid fa-chevron-down"></i></div>
                                    </div>
                                    <div class="collapse multi-collapse" id="topic' . $i . '">
                                        <div class="container">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Description</h5>' . $proposedTopic['description'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Expected output</h5>' . $proposedTopic['expected_output'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Skills</h5>' . $proposedTopic['skills'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Field(s) of study</h5>' . $proposedTopic['field_of_study'] . '</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-evenly">
                                        <a href="#" role="button" class="btn btn-secondary"><i class="fa-solid fa-pen-to-square"></i>&nbsp;Edit</a>
                                        <button type="button" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i>&nbsp;Remove</button>
                                    </div>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No proposed topics yet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
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
        $(".btn-danger").click(function(event) {
            event.preventDefault();

            var thisBtn = $(this);

            // get the index of the parent tr of the clicked button
            var topicID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("tbody tr").index(row);

            var confirmModal = $(createModal("Before you proceed!", "Remove this topic?"));

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
                    url: "includes/remove-topic-inc.php",
                    method: "POST",
                    data: {
                        topicID: topicID,
                        rowNum: rowNum
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
                            if (response.errors.empty) {
                                errorMessage = errorMessage + response.errors.empty + "<br/>";
                            }

                            if (response.errors.mismatch) {
                                errorMessage = errorMessage + response.errors.mismatch + "<br/>";
                            }

                            if (response.errors.sql) {
                                errorMessage = errorMessage + response.errors.sql + "<br/>";
                            }

                            var errorModal = $(createModal("Something went wrong", errorMessage));

                            // add a listener that forces a reload on the page when the modal is closed
                            errorModal.on('hidden.bs.modal', function() {
                                window.location.reload();
                            });

                            $("#requests-content").append(errorModal);

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

            $("#requests-content").append(confirmModal);
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