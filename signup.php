<?php


?>

<!DOCTYPE html>
<html>

<head>
    <title> Signup </title>
</head>

<body>
    <style type="text/css">
        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }

        #button {
            padding: 10px;
            width: 100px;
            color: white;
            background-color: lightblue;
            border: none;
        }

        #box {
            background-color: grey;
            margin: auto;
            width: 300px;
            padding: 20px;
        }
    </style>

    <section class="signup-form">
        <div id="box">
            <form action="includes/signup-inc.php" method="POST">
                <div style="font-size: 20px; margin: 10px; color: white;">Signup</div>
                <input id="text" type="text" name="userID" placeholder="userID..."><br /><br />
                <input id="text" type="password" name="password" placeholder="password..."><br /><br />
                <button id="button" type="submit" name="submit">Sign up</button><br /><br />

                <a href="login.php">Click to Login</a>
            </form>
        </div>

        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p>Fill in all fields!</p>";
            } else if ($_GET["error"] == "invalidpass") {
                echo "<p>Passwords must have uppercase letters, lowercase letters, numbers and special characters!</p>";
            } else if ($_GET["error"] == "userexists") {
                echo "<p>This user already exists!</p>";
            } else if ($_GET["error"] == "stmtfailed") {
                // user would not understand a failed statement, so just a generic error message will do
                echo "<p>Something went wrong, try again!</p>";
            }
        }
        ?>
    </section>

</body>

</html>