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

$fb_id = $_POST["fb_id"];

//check if user exist
$result = mysql_query("select `index`,first_name,last_name from Users where fb_id='$fb_id'");
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);
    $id = $row["index"];
    $name = $row["first_name"] . " " . $row["last_name"];
    
} else {
    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $date = date("Y-m-d");
    $gender = ($_POST["sex"] == "male") ? 0 : 1;
    $location = $_POST["location"];
    $bday = date("Y-m-d", strtotime($_POST["birthday"]));
    $name = $first . " " . $last;
    $school_id = $_POST["degree"];
    $year = $_POST["year"];
    $user = $_POST["email"];


    $result = mysql_query("INSERT INTO `Users`(`index`,`fb_id`, `email`, `first_name`, `last_name`, `country`, `sex`, `bday`, `school_id`,`year`, `status`, `created`) "
            . "VALUES (NULL,'$fb_id','$user','$first','$last','$location','$gender','$bday',$school_id,'$year','1','$date')");
    $id = mysql_insert_id();
}

$_SESSION["user"] = $user;
$_SESSION["name"] = $name;
$_SESSION["user_id"] = $id;

$response["debug1"] = mysql_error();
$response["debug"] = "INSERT INTO `Users`(`index`,`fb_id`, `email`, `first_name`, `last_name`, `country`, `sex`, `bday`, `school_id`,`year`, `status`, `created`) "
            . "VALUES (NULL,'$fb_id','$user','$first','$last','$location','$gender','$bday',$school_id,'$year','1','$date')";
$response["success"] = "1";

echo json_encode($response);
?>