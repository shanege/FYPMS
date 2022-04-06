<!DOCTYPE html>
<html>

<head>
    <title> Login </title>
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
            <form action="includes/login-inc.php" method="POST">
                <div style="font-size: 20px; margin: 10px; color: white;">Login</div>
                <input id="text" type="text" name="userID" placeholder="userID..."><br /><br />
                <input id="text" type="password" name="password" placeholder="password..."><br /><br />
                <button id="button" type="submit" name="submit">login</button><br /><br />

                <a href="signup.php">Click to Signup</a>
            </form>
        </div>

        <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyinput") {
                echo "<p>Fill in all fields!</p>";
            } else if ($_GET["error"] == "wrongpass") {
                echo "<p>Wrong password!</p>";
            } else if ($_GET["error"] == "nouser") {
                echo "<p>This user does not exist!</p>";
            }
        }
        ?>
    </section>
</body>

</html>