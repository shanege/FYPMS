<?php
require_once 'header.php';
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
}

$topic = getProposedTopic($con, $_POST['topicID']);

?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-50 my-3">
        <h1 class="my-5 mb-1 fw-bold">Edit topic details</h1>
        <form id="editTopicForm" method="POST">
            <fieldset>
                <div id="topicGroup" class="mb-3 ">
                    <label for="topic" class="form-label">Topic<span class="text-danger">&#42;</span></label>
                    <textarea id="topicInput" name="topic" class="form-control" placeholder="E.g., Social Media Sentiment Analysis Using Python Programming"><?php echo $topic['topic'] ?></textarea>
                </div>
                <div id="descriptionGroup" class="mb-3">
                    <label for="description" class="form-label">Description<span class="text-danger">&#42;</span></label>
                    <textarea id="descriptionInput" name="description" class="form-control" placeholder="E.g., Perform sentiment analysis on social media posts to gather user sentiment insights on a topic/user"><?php echo $topic['description'] ?></textarea>
                </div>
                <div id="expectedOutputGroup" class="mb-3">
                    <label for="expectedOutput" class="form-label">Expected output<span class="text-danger">&#42;</span></label>
                    <textarea id="expectedOutputInput" name="expectedOutput" class="form-control" placeholder="E.g., Develop a webapp that is able to collect a user's posts from a chosen social media in a timeframe and output their sentiment score"><?php echo $topic['expected_output'] ?></textarea>
                </div>
                <div id="skillsGroup" class="mb-3">
                    <label for="skills" class="form-label">Skills<span class="text-danger">&#42;</span></label>
                    <textarea id="skillsInput" name="skills" class="form-control" placeholder="E.g., Python, Data processing, etc."><?php echo $topic['skills'] ?></textarea>
                </div>
                <div id="fieldOfStudyGroup" class="mb-3">
                    <label for="fieldOfStudy" class="form-label">Field(s) of study<span class="text-danger">&#42;</span></label>
                    <textarea id="fieldOfStudyInput" name="fieldOfStudy" class="form-control" placeholder="E.g., Linguistics, Artificial Intelligence, Natural Language Processing, etc."><?php echo $topic['field_of_study'] ?></textarea>
                </div>
                <div id="message" class="rounded-3 mb-2 bg-body p-2 text-white bg-opacity-75 user-select-none">&nbsp;</div>
                <div class="d-flex justify-content-end">
                    <a href="manage-topics.php" type="button" class="btn btn-secondary mx-2">
                        Cancel
                    </a>
                    <input id="saveBtn" name="saveBtn" type="submit" class="btn btn-primary mx-2" value="Save changes">
                </div>
            </fieldset>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#editTopicForm').on('submit', function(event) {
            event.preventDefault();

            $(".invalid-feedback").remove();
            $(".form-control").removeClass("is-invalid");
            $("#message").html("&nbsp;").removeClass("bg-danger").addClass("bg-body");

            var formData = new FormData(this);
            formData.append("topicID", "<?php echo $_POST['topicID'] ?>");

            console.log(formData);

            $.ajax({
                url: "includes/edit-topic-inc.php",
                method: "POST",
                data: formData,
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
                        if (response.errors?.topic) {
                            $("#topicInput").addClass("is-invalid");
                            $("#topicGroup").append(
                                '<div class="invalid-feedback">' + response.errors.topic + "</div>"
                            );
                        }

                        if (response.errors?.description) {
                            $("#descriptionInput").addClass("is-invalid");
                            $("#descriptionGroup").append(
                                '<div class="invalid-feedback">' + response.errors.description + "</div>"
                            );
                        }

                        if (response.errors?.expectedOutput) {
                            $("#expectedOutputInput").addClass("is-invalid");
                            $("#expectedOutputGroup").append(
                                '<div class="invalid-feedback">' + response.errors.expectedOutput + "</div>"
                            );
                        }

                        if (response.errors?.skills) {
                            $("#skillsInput").addClass("is-invalid");
                            $("#skillsGroup").append(
                                '<div class="invalid-feedback">' + response.errors.skills + "</div>"
                            );
                        }

                        if (response.errors?.fieldOfStudy) {
                            $("#fieldOfStudyInput").addClass("is-invalid");
                            $("#fieldOfStudyGroup").append(
                                '<div class="invalid-feedback">' + response.errors.fieldOfStudy + "</div>"
                            );
                        }

                        if (response.errors?.sql) {
                            $("#message").html(response.errors.sql).removeClass("bg-body").addClass("bg-danger");
                        }
                    } else {
                        // make a hidden form to send POST variable to tell add/remove topic page that the edit was a success
                        var form = $(document.createElement('form'));
                        $(form).attr("action", "manage-topics.php");
                        $(form).attr("method", "POST");
                        $(form).css("display", "none");

                        var proposeTopicResult = $("<input>")
                            .attr("type", "text")
                            .attr("name", "editTopicResult")
                            .val("success");
                        $(form).append($(proposeTopicResult));

                        form.appendTo(document.body);
                        $(form).submit();
                    }

                    $('#saveBtn').attr('disabled', false);
                    $('#saveBtn').val('Save changes');
                }
            })
        });
    });
</script>
</body>

</html>