<?php
require_once "includes/functions-inc.php";
require_once "includes/connection-inc.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION["role"] == "supervisor") {
    $tasks = getTasksForPair($con, $_GET['student'], $_SESSION['userID']);
} else if ($_SESSION["role"] == "student") {
    $supervisor = requestExists($con, $_SESSION['userID']);

    if ($supervisor !== false) {
        $tasks = getTasksForPair($con, $_SESSION['userID'], $supervisor['supervisorID']);
    }
}

if (!empty($tasks)) {
    $completedTasks = 0;
    foreach ($tasks as $task) {
        if ($task['status'] == "Completed") {
            $completedTasks++;
        }
    }

    $progress = ($completedTasks / count($tasks)) * 100;
} else {
    $progress = 0;
}
?>

<div class="d-flex justify-content-between">
    <h3>Task progress:
        <?php
        if (!empty($tasks)) {
            echo $completedTasks . ' / ' . count($tasks);
        } else {
            echo '0 / 0';
        }
        ?>
    </h3>
    <?php
    if ($_SESSION["role"] == "supervisor") {
        echo '
        <button type="button" class="btn btn-primary mx-2 mb-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">
            Add a task
        </button>';
    }
    ?>
</div>

<div class="progress mb-4">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="<?php echo $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $progress ?>%"></div>
</div>

<table class="table table-hover align-middle w-100" style="table-layout: fixed;">
    <colgroup>
        <col span="1" style="width:2.5%;">
        <col span="1" style="width:40%;">
        <col span="1" style="width:40%;">
        <col span="1" style="width:17.5%;">
        <col span="1" style="width:10%;">
    </colgroup>
    <thead class="table-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Deadline</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <?php
    if (!empty($tasks)) {
        $i = 0;
        foreach ($tasks as $task) {
            echo '<tr style="transform: rotate(0);" class=';

            $deadline = new DateTime($task['deadline_at'], new DateTimeZone('Asia/Kuala_Lumpur'));

            // get the current DateTime
            $now = new DateTime("now", new DateTimeZone('Asia/Kuala_Lumpur'));

            if ($task['status'] == "Completed") {
                echo '"table-success">';

                $diff = $deadline->diff($now);

                if ($diff->days < 7) {
                    if ($diff->d == 0) {
                        if ($diff->h == 0) {
                            if ($diff->m == 0) {
                                $timeLeft = $diff->s;
                            } else {
                                $timeLeft = $diff->m . ' minute(s) ' . $diff->s . ' second(s)';
                            }
                        } else {
                            $timeLeft = $diff->h . ' hour(s) ' . $diff->m . ' minute(s)';
                        }
                    } else {
                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                    }
                } else {
                    $timeLeft = $deadline->format('D, d F Y, H:i A');
                }

                $statusIcon = '<span class="text-success"><i class="bi bi-check-circle text-success fs-4"></i> Completed</span>';
            } else if ($deadline < $now) {
                echo '"table-danger">';

                $timeLeft = $deadline->format('D, d F Y, H:i A');

                $statusIcon = '<span class="text-danger"><i class="bi bi-exclamation-circle fs-4"></i> Overdue</span>';
            } else {
                echo '"table-light">';

                $diff = $deadline->diff($now);

                if ($diff->days < 7) {
                    if ($diff->d == 0) {
                        if ($diff->h == 0) {
                            if ($diff->m == 0) {
                                $timeLeft = $diff->s;
                            } else {
                                $timeLeft = $diff->m . ' minute(s) ' . $diff->s . ' second(s)';
                            }
                        } else {
                            $timeLeft = $diff->h . ' hour(s) ' . $diff->m . ' minute(s)';
                        }
                    } else {
                        $timeLeft = $diff->d . ' day(s) ' . $diff->h . ' hour(s)';
                    }
                } else {
                    $timeLeft = $deadline->format('D, d F Y, H:i A');
                }

                $statusIcon = '<span><i class="bi bi-clock fs-4"></i> Ongoing</span>';
            }

            echo '
                <td><a href="view-task.php?taskID=' . $task['taskID'] . '" class="stretched-link"></a>' . ++$i . '</td>
                <td><span class="d-inline-block text-truncate w-100">' . $task['title'] . '</span></td>
                <td class="text-secondary"><span class="d-inline-block text-truncate w-100">';
            echo $task['description'] == "" ? "No description" : $task['description'];
            echo '</span></td><td>' . $timeLeft . '</td><td class="text-center">' . $statusIcon . '</td></tr>';
        }
    } else {
        echo '<tr><td colspan="5">No tasks yet.</td></tr>';
    }
    ?>
</table>