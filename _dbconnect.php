<?php
$server = "localhost:3306";
$username = "root";
$password = "";
$database = "users";

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn){
    die("Error". mysqli_connect_error());
}

?>
