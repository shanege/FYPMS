<?php

if (isset($_POST["submit"])) {
    // something was posted
    $userID = $_POST['userID'];
    $password = $_POST['password'];

    require_once 'connection-inc.php';
    require_once 'functions-inc.php';

    if (emptyInput($userID, $password) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (invalidPassword($password) !== false) {
        header("location: ../signup.php?error=invalidpass");
        exit();
    }

    if (userExists($con, $userID) !== false) {
        header("location: ../signup.php?error=userexists");
        exit();
    }

    // save to database
    createUser($con, $userID, $password);
} else {
    header("location: ../signup.php");
}
