<?php
require_once 'header.php';
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <?php
        $supervisorDetailsList = getAllSupervisors($con);

        if (!empty($supervisorDetailsList)) {
            echo
            '<div class="table-responsive">
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


            // get current semester
            $thisYear = date('Y');
            $thisMonth = date('n');

            // if within April and September, set semester as 4, else set as 9
            $semesterMonth = ($thisMonth >= 4 && $thisMonth < 9) ? 4 : 9;
            $thisSemester = $thisYear . "0" . $semesterMonth;
            $quota = getSupervisorStudentQuota($con, $thisSemester);

            foreach ($supervisorDetailsList as $key => $value) {
                echo
                '<tr>
                    <td><a href="profile.php?id=' . $value["supervisorID"] . '">' . $value["name"] . '</a></td>
                    <td>' . $value["research_areas"] . '</td>
                    <td>' . $value["email"] . '</td>';

                $studentCount = countStudentsPerSupervisor($con, $value["supervisorID"]);

                if (!$quota) {
                    echo '<td>No quota set yet</td>';
                } else {
                    echo '<td>' . $studentCount . ' / ' . $quota . '</td>';
                }
                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
        ?>
    </div>
</div>