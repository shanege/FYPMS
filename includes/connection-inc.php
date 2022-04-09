<?php
if (count(get_included_files()) == 1) exit("Direct access not permitted.");

$dbhost = "localhost";
$dbname = "fypms";
$dbuser = "root";
$dbpass = "";

try {
    $con = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die();
}
