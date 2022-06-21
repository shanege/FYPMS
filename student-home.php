<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");
?>

<div class="container w-75">
    <h1 class="mt-2 mb-1 fw-bold">Home page</h1>
    <div class="mb-3 fst-italic">Choose your action.</div>
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-magnifying-glass fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Find a supervisor</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="manage-fyp.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-bars-progress fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage FYP</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="proposed-topics-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-pen-clip fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View proposed topics</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="thesis-archive.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-box-archive fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View thesis archive</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $('.fa-magnifying-glass').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-bars-progress').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-pen-clip').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });
</script>
</body>

</html>