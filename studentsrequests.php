<?php
if ($userData["role"] != "supervisor") {
    exit("You are not allowed to access this page");
}

echo
'<div class="table-responsive my-3">
    <table class="table table-striped align-middle" style="width:80rem;">
        <colgroup>
            <col span="1" style="width:20%;">
            <col span="1" style="width:60%;">
            <col span="1" style="width:20%;">
        </colgroup>
        <thead class="table-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Working title</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>';

if (!empty($pendingStudentsIDs)) {
    foreach ($pendingStudentsIDs as $pendingStudentID) {
        $student = getStudent($con, $pendingStudentID);

        echo
        '<tr id="' . $pendingStudentID . '">
            <td>
                <div class="d-flex w-40"><a href="profile.php?id=' . $pendingStudentID . '">' . $student["name"] . '</div></a>
            </td>
            <td>
                <div class="d-flex w-40">' . $student["working_title"] . '</div>
            </td>
            <td>
                <div class="d-flex justify-content-evenly">
                    <button type="button" class="btn btn-success"><i class="bi bi-person-check"></i>&nbsp;Accept</button>
                    <button type="button" class="btn btn-danger"><i class="bi bi-person-x"></i>&nbsp;Decline</button>
                </div>
            </td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="3">No student requests at the moment.</td></tr>';
}
?>
<script>
    $(document).ready(function() {
        $(".btn-success").click(function(event) {
            var thisBtn = $(this);

            event.preventDefault();

            // get the index of the parent tr of the clicked button
            var studentID = thisBtn.closest("tr").prop('id');

            // get row number of the clicked button
            var row = thisBtn.closest("tr");
            var rowNum = $("tbody tr").index(row);

            $.ajax({
                url: "includes/accept_student-inc.php",
                method: "POST",
                data: {
                    studentID: studentID,
                    rowNum: rowNum
                },
                cache: false,
                beforeSend: function() {
                    thisBtn.attr('disabled', 'disabled');
                    thisBtn.siblings(".btn-danger").attr('disabled', 'disabled');
                },
                success: function(data) {

                }
            })
        })
    })
</script>