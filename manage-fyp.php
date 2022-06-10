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
                    <i class="bi bi-search card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text">General documents</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-auto">
            <a class="btn" href="#" role="button">
                <div class="card text-center" style="width: 18rem;">
                    <i class="bi bi-card-heading card-img-top " style="font-size: 8rem;"></i>
                    <div class="card-body">
                        <p class="card-text"> Manage FYP tasks </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>