<!DOCTYPE html>
<html>

<head>
    <title> Login </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body class="bg-primary bg-gradient bg-opacity-25">
    <div class="position-relative">
        <div class="position-absolute top-0 start-50 translate-middle-x w-25 my-5">
            <section class="signup-form">
                <div class="card p-3">
                    <form action="includes/login-inc.php" method="POST">
                        <fieldset>
                            <legend>Login to FYPMS</legend>
                            <div class="mb-3">
                                <label for="userIDInput" class="form-label">User ID</label>
                                <input id="userIDInput" type="text" name="userID" class="form-control" placeholder="userID...">
                            </div>
                            <div class="mb-3">
                                <label for="passwordInput" class="form-label">Password</label>
                                <input id="passwordInput" type="password" name="password" class="form-control" placeholder="password...">
                            </div>
                            <button id="button" type="submit" name="submit" class="btn btn-primary mb-3 w-100">login</button>
                        </fieldset>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>