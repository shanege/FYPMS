<?php
require_once 'header.php';
// if ($userData["role"] != "student") {
//     exit("You are not allowed to access this page");
// }
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x">
        <?php
        $supervisorDetailsList = getAllLecturers($con);

        if (!empty($supervisorDetailsList)) {
            echo '<div class="table-responsive">
                    <table class="table table-striped" style="width:80rem;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Research Area(s)</th>
                                <th scope="col">Email</th>
                                <th scope="col">Quota</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($supervisorDetailsList as $key => $value) {
                echo '<tr>';
                echo '<td><a href="supervisor.php?id=' . $value["supervisorID"] . '">' . $value["name"] . '</a></td>';
                echo '<td>' . $value["research_area"] . '</td>';
                echo '<td>' . $value["email"] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
        ?>
    </div>
</div>

</body>

</html>