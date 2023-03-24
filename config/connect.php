<?php
$url = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "klinika";

try {
    $pdo = new PDO("mysql:host=$url; dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();

}