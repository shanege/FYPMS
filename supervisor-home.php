<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>

<div class="container w-75">
    <h1 class="mt-5 mb-1 fw-bold">Home page</h1>
    <div class="mb-5 fst-italic">Choose your action.</div>
    <div class="row justify-content-between mx-auto">
        <div class="col-md-auto">
            <a class="btn" href="manage-supervisees.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-user-graduate fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage supervised students</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-magnifying-glass fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View supervisor list</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-topics.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-file-invoice fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Add/remove proposed topics</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-topics.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-box-archive fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Archive student thesis</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $('.fa-file-invoice').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-magnifying-glass').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-user-graduate').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-box-archive').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });
</script>
</body>

</html>