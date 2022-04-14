<?php
require_once 'header.php';
if ($user_data["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

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

Manage supervisors details
<form id="manageSupervisorsExcel" method="POST" enctype="multipart/form-data">
    <table>
        <tr>
            <td width="25%">Select Excel file</td>
            <td width="50%"><input type="file" name="supervisorFile"></td>
            <td width="25%"><input type="submit" name="manageSupervisors" id="manageSupervisors" value="Import"></td>
        </tr>
    </table>
</form>
<span id="message"></span>
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
                    $('#message').html(data);
                    $('#registerUsersExcel')[0].reset();
                    $('#registerUsers').attr('disabled', false);
                    $('#registerUsers').val('Import');
                }
            })
        });
    });
    $(document).ready(function() {
        $('#manageSupervisorsExcel').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/supervisor_details-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#manageSupervisors').attr('disabled', 'disabled');
                    $('#manageSupervisors').val('Importing...');
                },
                success: function(data) {
                    $('#message').html(data);
                    $('#manageSupervisorsExcel')[0].reset();
                    $('#manageSupervisors').attr('disabled', false);
                    $('#manageSupervisors').val('Import');
                }
            })
        });
    });
</script>