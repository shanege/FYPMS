<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="container w-75">
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="register-users.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/register-users.png" class="card-img-top img-fluid p-3" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">Register users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-students.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-students.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">Manage students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-supervisors.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-supervisors.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">Manage supervisors</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>

</html>