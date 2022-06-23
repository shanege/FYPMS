<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>

<div class="container w-75">
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-users.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-users.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">Manage users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/view-supervisor-list.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">View supervisor list</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisor-student-pairs.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/view-supervisor-student-pairs.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bolder">View supervisor/student pairs</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>

</html>