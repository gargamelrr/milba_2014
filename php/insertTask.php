<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

if (isset($_POST["taskName"]) && isset($_POST["date1"]) && isset($_POST["taskTime"])
        && isset($_POST["radiodifficulty"]) && isset($_POST["taskdetails"])) {

    $response = array();
    $taskName = $_POST['taskName'];
    $dueDate = $_POST['date1'];
    $taskTime = $_POST['taskTime'];
    $radioDifficulty = $_POST['radiodifficulty'];
    $taskDetails = $_POST['taskdetails'];
    $courseID = $_POST['courseID'];
    $taskID = $_POST['taskID'];
   // $date = date('Y-m-d H:i:s', time());
    $date = date('Y-m-d H:i:s'); 

    //SQL column for difficulty is boolean, ajust it. 
    if($radioDifficulty == "hard") {
        $radioDifficulty = 1;
    } else {
        $radioDifficulty = 0;
    }
    
    //Checking if user has pressed on the edit button
    if($taskID == -1) {
        $taskID = NULL;
    }
    
    $result = mysql_query("INSERT INTO `Tasks`(`index`, `course_id`, `name`, `due_date`, `description`, `difficulty`, `creator`, `status`, `created`) "
            . "VALUES ('$taskID', '$courseID', '$taskName', '$dueDate . $taskTime', '$taskDetails', '$radioDifficulty', 'Ronny', '111', '$date')"
            . "ON DUPLICATE KEY UPDATE `name` = '$taskName', `due_date`= '$dueDate . $taskTime', `description` = '$taskDetails'  ");

    
// check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = "sql failed nigger";
    }
} else {
    //error
    $response["success"] = 0;
    $response["message"] = "failed at the entry if";
}

echo json_encode($response);
?>
