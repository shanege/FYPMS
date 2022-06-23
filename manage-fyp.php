<?php
require_once 'header.php';
if ($userData["role"] != "student") {
    exit("You are not allowed to access this page");
}
?>
<div class="container w-75">
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="general-documents.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/general-documents.png" class="card-img-top img-fluid my-4" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">General documents</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="student-tasks.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-fyp.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">Manage FYP tasks</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>

</html>