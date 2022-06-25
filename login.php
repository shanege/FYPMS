<!DOCTYPE html>
<html>

<head>
    <title> Login </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
</head>

<body class="bg-body login-bg">
    <div class="position-relative">
        <div class="position-absolute top-0 start-50 translate-middle-x my-5">
            <h1 class="fw-bolder text-center">Xiamen University Malaysia<br />Final Year Project Management System</h1>
            <form action="includes/login-inc.php" method="POST">
                <fieldset>
                    <input id="userIDInput" type="text" name="userID" class="form-control login-input rounded-pill mt-4 mb-3" placeholder="User ID">
                    <input id="passwordInput" type="password" name="password" class="form-control login-input rounded-pill mb-3" placeholder="Password">
                    <button id="button" type="submit" name="submit" class="btn login-btn rounded-pill w-100 mb-3">Log in</button>
                </fieldset>
            </form>

            <?php
            if (isset($_GET["error"])) {
                if ($_GET["error"] == "emptyinput") {
                    echo "<p class='text-danger'>Fill in all fields!</p>";
                } else if ($_GET["error"] == "wrongpassword") {
                    echo "<p class='text-danger'>Wrong password!</p>";
                } else if ($_GET["error"] == "nouser") {
                    echo "<p class='text-danger'>This user does not exist!</p>";
                }
            }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>