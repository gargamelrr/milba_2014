<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

if (isset($_POST["taskName"]) && isset($_POST["date1"])&& isset($_POST["taskTime"])
        && isset($_POST["radiodifficulty:"])&& isset($_POST["taskdetails"])) {

   $response = array();
    $taskName = $_POST['taskName'];
    $dueDate = $_POST['date1'];
    $taskTime = $_POST['taskTime'];
    $radioDifficulty = $_POST['radiodifficulty'];
    $taskDetails = $_POST['taskdetails'];
    $date = date("Y-m-d");
    
    $result = mysql_query("INSERT INTO `Tasks`(`index`, `name`, `dueDate`, `description`, `creator`, `status`, `status`, `created`) "
            . "VALUES (NULL, '$taskName', '$dueDate . $taskTime', '$taskDetails', 'Ronny', 'active', '$date')");

    
// check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = mysql_error();
    }
} else {
    //error
    $response["success"] = 0;
    $response["message"] = mysql_error();
}

echo json_encode($response);
?>
