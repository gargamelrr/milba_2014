<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT;

session_start();

$user = $_POST["email"];
$first = $_POST["first_name"];
$last = $_POST["last_name"];
$date = date("Y-m-d");
$gender = ($_POST["sex"] == "male") ? 0 : 1;
$location = $_POST["location"];
$bday = date("Y-m-d",$_POST["birthday"]);
$name = $first . " " . $last;

mysql_query("INSERT INTO `Users`(`index`, `email`, `first_name`, `last_name`, `country`, `sex`, `bday`, `school_id`, `status`, `created`) "
        . "VALUES (NULL,'$user','$first','$last','$location','$gender','$bday',[value-8],'active','$date')");

$_SESSION["user"] = $user;
$_SESSION["name"] = $name;

echo json_encode($_POST);
?>