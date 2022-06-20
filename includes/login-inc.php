<?php

if (isset($_POST["submit"])) {
    // something was posted
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    if (emptyInput($userID, $password) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($con, $userID, $password);
} else {
    header("location: ../login.php");
    exit();
}
