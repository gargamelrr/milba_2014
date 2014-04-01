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
$response = array();
//$response["cookieData"] =array();
if( !isset( $_SESSION ) ){
    session_start();
}
$fb_id = $_POST["fb_id"];
    
//check if user exist
$result = mysql_query("select `index`,first_name,last_name,email from Users where fb_id='$fb_id'");
$count = mysql_num_rows($result);
if (mysql_num_rows($result) > 0) {
    $row = mysql_fetch_array($result);
    $id = $row["index"];
    $name = $row["first_name"] . " " . $row["last_name"];
    $user = $row["email"];
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
    
//array_push($response["cookieData"], $year);
//array_push($response["cookieData"], $user);
//array_push($response["cookieData"], $name);
//aray_push($response["cookieData"], $id);
//array_push($response["cookieData"], $school_id);
//            
            
if (isset($_POST["friends"])) {
    
    $friends_str = "";
    foreach ($_POST["friends"] as $friend) {
        $friends_str .= ", " . $friend["id"];
    }
    $friends_str = substr($friends_str, 1);
        
    $result = mysql_query("select `index` from Users where fb_id in ($friends_str)");
    $fbFriends = array();
    while ($row = mysql_fetch_array($result)) {
        array_push($fbFriends, $row);
    }
    array_push($response["cookieData"], $fbFriends);
}
    
$response["debug1"] = $_SESSION["user_id"];
//$response["debug"] = $_SESSION["user"] . " " . $_SESSION["user_id"];
$response["success"] = "1";
    
echo json_encode($response);
?>