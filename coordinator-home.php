<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>

<div class="container w-75">
    <h1 class="mt-2 mb-1 fw-bold">Home page</h1>
    <div class="mb-3 fst-italic">Choose your action.</div>
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-users.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-table fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage users</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-magnifying-glass fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View supervisor list</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisor-student-pairs.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-user-group fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View supervisor/student pairs</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $('.fa-table').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-magnifying-glass').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-user-group').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });
</script>
</body>

</html>