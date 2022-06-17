<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-auto">
            <a class="btn" href="manage-users.php" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="fa-solid fa-table fa-10x my-3" style="--fa-beat-scale: 1.1;"></i>
                    <div class="card-body">
                        <p class="card-text">Manage users</p>
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