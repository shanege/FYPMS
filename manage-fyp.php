<?php
require_once 'header.php';
if ($userData["role"] != "student") {
    exit("You are not allowed to access this page");
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="general-documents.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-file fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">General documents</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="student-tasks.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-bars-progress fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text"> Manage FYP tasks </p>
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