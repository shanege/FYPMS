<?php
require_once 'header.php';

if ($userData["role"] == "coordinator") {
    exit("You are not allowed to access this page");
}
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x my-3">
        <h1 class="mt-5 mb-1 fw-bold">List of proposed topics</h1>
        <div class="mb-5 fst-italic">Click on a topic to expand its details.</div>
        <div class="table-responsive my-3">
            <table class="table table-striped align-middle" style="width:80rem;">
                <colgroup>
                    <col span="1" style="width:5%;">
                    <col span="1" style="width:15%;">
                    <col span="1" style="width:80%;">
                </colgroup>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Supervisor</th>
                        <th scope="col">Proposed topic</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $proposedTopics = getAllProposedTopics($con);
                    if (!empty($proposedTopics)) {
                        $i = 0;

                        // a transform is applied to 2nd td so that the stretched link does not spread over it
                        foreach ($proposedTopics as $proposedTopic) {
                            $supervisorDetails = getSupervisor($con, $proposedTopic['supervisorID']);
                            echo
                            '<tr id="' . $proposedTopic['topicID'] . '" class="position-relative">
                                <td>' . ++$i . '</td>
                                <td>
                                    <a href="profile.php?id=' . $proposedTopic['supervisorID'] . '">';
                            echo $supervisorDetails["name"] == "" ? $proposedTopic['supervisorID'] : $supervisorDetails["name"];
                            echo
                            '</a>
                                </td>
                                <td style="transform: rotate(0);">
                                    <div class="d-flex text-break">
                                        <button type="button" class="stretched-link btn btn-outline-none shadow-none flex-grow-1 text-start" data-bs-toggle="collapse" data-bs-target="#topic' . $i . '" aria-expanded="false" aria-controls="topic' . $i . '">' . $proposedTopic['topic'] . '</button>
                                        <div class="align-self-center"><i class="fa-solid fa-chevron-down"></i></div>
                                    </div>
                                    <div class="collapse multi-collapse" id="topic' . $i . '">
                                        <div class="container">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Description</h5>' . $proposedTopic['description'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Expected output</h5>' . $proposedTopic['expected_output'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Skills</h5>' . $proposedTopic['skills'] . '</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="py-3 text-break"><h5>Field(s) of study</h5>' . $proposedTopic['field_of_study'] . '</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No proposed topics yet.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>