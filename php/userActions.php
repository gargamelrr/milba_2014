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

//check if user exist
$result = mysql_query("select index from Users where email='$user'");
if (mysql_num_rows($result) > 0) {
    // do we need to update the fields? maybe..
    $row = mysql_fetch_array($result);
    $id = $row["index"];
    
} else {
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $date = date("Y-m-d");
    $gender = ($_POST["sex"] == "male") ? 0 : 1;
    $location = $_POST["location"];
    $bday = date("Y-m-d", strtotime($_POST["birthday"]));
    $name = $first . " " . $last;
    $school_id = $_POST["degree"];

    $result = mysql_query("INSERT INTO `Users`(`index`, `email`, `first_name`, `last_name`, `country`, `sex`, `bday`, `school_id`, `status`, `created`) "
            . "VALUES (NULL,'$user','$first','$last','$location','$gender','$bday',$school_id,'active','$date')");
}
$id = mysql_insert_id();

$_SESSION["user"] = $user;
$_SESSION["name"] = $name;
$_SESSION["user_id"] = $id;

$response["debug"] = "INSERT INTO `Users`(`index`, `email`, `first_name`, `last_name`, `country`, `sex`, `bday`, `school_id`, `status`, `created`) "
            . "VALUES (NULL,'$user','$first','$last','$location','$gender','$bday',$school_id,'active','$date')";
$response["success"] = "1";

echo json_encode($response);
?>