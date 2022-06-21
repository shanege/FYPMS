<?php
require_once 'header.php';
?>

<div class="position-relative">
    <div class="position-absolute top-0 start-50 translate-middle-x w-50 my-3">
        <h1 class="mt-5 mb-1 fw-bold">General FYP documents</h1>
        <div class="mb-5 fst-italic">Click on a link to download.</div>
        <h3>FYP1</h3>
        <div class="list-group mb-3" id="FYP1documents">
            <p>Loading files <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></p>
        </div>
        <h3>FYP2</h3>
        <div class="list-group mb-3" id="FYP2documents">
            <p>Loading files <i class="fa-solid fa-spinner fa-spin-pulse fa-spin-reverse"></i></p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#FYP1documents").load("includes/display-files-inc.php", {
            folder: "General documents/FYP1",
            style: "group"
        });

        $("#FYP2documents").load("includes/display-files-inc.php", {
            folder: "General documents/FYP2",
            style: "group"
        });
    });
</script>