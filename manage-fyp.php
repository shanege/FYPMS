<?php
require_once 'header.php';
if ($userData["role"] != "student") {
    exit("You are not allowed to access this page");
}
?>
<div class="container w-75">
    <h1 class="mt-2 mb-1 fw-bold">Home page</h1>
    <div class="mb-3 fst-italic">Choose your action.</div>
    <div class="row justify-content-center mx-auto text-center w-75">
        <div class="col-md-auto mb-3">
            <a class="btn" href="general-documents.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-file fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">General documents</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto mb-3">
            <a class="btn" href="student-tasks.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-bars-progress fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage FYP tasks</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    $('.fa-file').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });

    $('.fa-bars-progress').hover(function() {
        $(this).addClass('fa-beat');
    }, function() {
        $(this).removeClass('fa-beat');
    });
</script>
</body>

</html>