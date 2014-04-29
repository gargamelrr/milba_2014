<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/Global_Func.php';

// connecting to db
$db = new DB_CONNECT();
session_start();

$user_id = $_SESSION["user_id"];
$nameUser = $_SESSION["name"];

if (isset($_POST["taskName"]) && isset($_POST["date1"]) && isset($_POST["taskTime"]) && isset($_POST["radiodifficulty"]) && isset($_POST["taskdetails"])) {

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
    if ($radioDifficulty == "hard") {
        $radioDifficulty = 1;
    } else {
        $radioDifficulty = 0;
    }

    //Checking if user has pressed on the edit button
    if ($taskID == -1) {
        $taskID = NULL;
    }

    if ($courseID == -1) {
        $result = mysql_query("INSERT INTO `Users_PrivateTasks`(`index`,`student_id`, `name`, `due_date`, `description`, `difficulty`, `creator`, `status`, `created`) "
                . "VALUES ('$taskID', '$user_id', '$taskName', '$dueDate . $taskTime', '$taskDetails', '$radioDifficulty', '$user_id', '1', '$date')"
                . "ON DUPLICATE KEY UPDATE `name` = '$taskName', `due_date`= '$dueDate . $taskTime', `description` = '$taskDetails'  ");
    } else {
        $result = mysql_query("INSERT INTO `Tasks`(`index`, `course_id`, `name`, `due_date`, `description`, `difficulty`, `creator`, `status`, `created`) "
                . "VALUES ('$taskID', '$courseID', '$taskName', '$dueDate . $taskTime', '$taskDetails', '$radioDifficulty', '$user_id', '1', '$date')"
                . "ON DUPLICATE KEY UPDATE `name` = '$taskName', `due_date`= '$dueDate . $taskTime', `description` = '$taskDetails'  ");

        //notify all users in course
        $message = "$nameUser just gave a sheet in $courseID";
        //bring all registerID
        $resultGCM = mysql_query("select gcm from Users join Users_Courses where Users.index = Users_Courses.student_id and course_id = $courseID");
        $regIDArray = mysql_fetch_array($resultGCM);
        sendGCM($regIDArray, $message);
    }

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
