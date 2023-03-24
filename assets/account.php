<?php require "../config/database.php";

$login = $_POST['login'];
$password =  $_POST['password'];

Login($login, $password);

?>