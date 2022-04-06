<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "fypms";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
