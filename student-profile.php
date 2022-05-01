<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <?php
        $studentDetails = getStudent($con, $id);

        if (!$studentDetails) {
            echo 'This student could not be found.';
        } else {
            echo
            '<div class="table-responsive">
                <table class="table table-striped" style="width:50rem;">
                    <tbody>
                        <tr>
                            <th scope="row">Name</th>
                            <td>' . $studentDetails["name"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td>' . $studentDetails["email"] . '</td>
                        </tr>
                        <tr>
                            <th scope="row">Working Title</th>
                            <td>' . $studentDetails["working_title"] . '</td>
                        </tr>
                    </tbody>
                </table>
            </div>';
        }

        if ($userData['role'] == "student" && $userData['userID'] == $id) {
            echo
            '<div class="d-flex justify-content-end">
                <a href="edit-profile.php" type="button" class="btn btn-primary mx-2">
                    Edit Profile
                </a>
            </div>';
        }
        ?>
    </div>
    <?php
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
    var editSuccessToast = document.getElementById('editSuccessToast');
    if (editSuccessToast) {
        var toast = new bootstrap.Toast(editSuccessToast);

        toast.show();

        editSuccessToast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
</script>
</body>

</html>