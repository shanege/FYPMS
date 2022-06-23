<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>

<div class="container w-75">
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-supervisees.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-supervised-students.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bold">Manage supervised students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/view-supervisor-list.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bold">View supervisor list</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-topics.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-proposed-topics.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bold">Manage proposed topics</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="thesis-archive.php" role="button">
                <div class="card shadow text-center p-3" style="width: 18rem; height: 18rem;">
                    <img src="images/manage-student-thesis-archive.png" class="card-img-top img-fluid" alt="..." style="max-height: 90%;">
                    <div class="card-body d-flex align-items-end justify-content-center p-0">
                        <p class="card-text fw-bold">Manage student thesis archive</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

</body>

</html>