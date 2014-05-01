<?php

//include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/Global_Func.php';

// connecting to db
$db = new DB_CONNECT();
session_start();

$user_id = $_SESSION["user_id"];
$nameUser = $_SESSION["name"];

if (isset($_POST["sound"]) && isset($_POST["custom"]) && isset($_POST["days"]) && isset($_POST["task"]) && isset($_POST["group"])) {
    $sound = $_POST["sound"];
    $custom = $_POST["custom"];
    $days = $_POST["days"];
    $task = $_POST["task"];
    $group = $_POST["group"];
    
    
    $result = mysql_query("UPDATE Users SET sound='$sound', customAlert='$custom', alertDays= '$days', taskNoti='$task', groupNoti='$group' WHERE Users.index='$user_id'");
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = "sql failed";
    }
} else {
    //error
    $response["success"] = 0;
    $response["message"] = "Info was not recieved well by client side";
}

echo json_encode($response);

?>