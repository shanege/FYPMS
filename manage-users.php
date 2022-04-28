<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="manage-students.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-people card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text">Add users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-students.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-people card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-supervisors.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-people card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage supervisors</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
Register users by bulk
<form id="registerUsersExcel" method="POST" enctype="multipart/form-data">
    <table>
        <tr>
            <td width="25%">Select Excel file</td>
            <td width="50%"><input type="file" name="registerFile"></td>
            <td width="25%"><input type="submit" name="registerUsers" id="registerUsers" value="Import"></td>
        </tr>
    </table>
</form>
<span id="response"></span>


</body>

</html>
<script>
    $(document).ready(function() {
        $('#registerUsersExcel').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/bulk_register-inc.php",
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
                    $('#response').html(data);
                    $('#registerUsersExcel')[0].reset();
                    $('#registerUsers').attr('disabled', false);
                    $('#registerUsers').val('Import');
                }
            })
        });
    });
</script>