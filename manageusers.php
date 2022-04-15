<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
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
<span id="response1"></span>

Update supervisors details
<form id="updateSupervisorsDetailsExcel" method="POST" enctype="multipart/form-data">
    <table>
        <tr>
            <td width="25%">Select Excel file</td>
            <td width="50%"><input type="file" name="supervisorFile"></td>
            <td width="25%"><input type="submit" name="updateSupervisors" id="updateSupervisors" value="Import"></td>
        </tr>
    </table>
</form>
<span id="response2"></span>
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
                    $('#response1').html(data);
                    $('#registerUsersExcel')[0].reset();
                    $('#registerUsers').attr('disabled', false);
                    $('#registerUsers').val('Import');
                }
            })
        });
        $('#updateSupervisorsDetailsExcel').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "includes/supervisor_details-inc.php",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('#updateSupervisors').attr('disabled', 'disabled');
                    $('#updateSupervisors').val('Importing...');
                },
                success: function(data) {
                    $('#response2').html(data);
                    $('#updateSupervisorsDetailsExcel')[0].reset();
                    $('#updateSupervisors').attr('disabled', false);
                    $('#updateSupervisors').val('Import');
                }
            })
        });
    });
</script>