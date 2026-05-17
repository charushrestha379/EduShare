<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "eduswap";

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
mysqli_query($conn, $sql);

mysqli_select_db($conn, $dbname);
?>
