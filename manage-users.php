<?php
require_once 'header.php';
if ($userData["role"] != "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="container w-75">
    <h1 class="mt-5 mb-1 fw-bold">Manage users</h1>
    <div class="mb-5 fst-italic">Choose your action.</div>
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="add-users.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-user-plus fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Add users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-students.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-user-graduate fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-supervisors.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-chalkboard-user fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage supervisors</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $('.fa-user-plus').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-user-graduate').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-chalkboard-user').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });
</script>
</body>

</html>