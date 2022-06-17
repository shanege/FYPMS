<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="supervisors-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-magnifying-glass fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Find a supervisor</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="manage-fyp.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-bars-progress fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage FYP</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="proposed-topics-list.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-pen-clip fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">View proposed topics</p>
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