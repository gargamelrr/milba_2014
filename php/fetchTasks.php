<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$courseID = $_GET['courseID'];
$response["debug2"] = $courseID;
if($courseID == -1) {  
    $result = mysql_query("SELECT `index`,name, DATE_FORMAT(due_date, '%d.%m.%y %H:%i') as due_date ,description FROM `Users_PrivateTasks` Where Users_PrivateTasks.student_id=$user_id and DATEDIFF(NOW(), due_date) <= 2 order by due_date");
} else {
$resultUser = mysql_query("SELECT * FROM Users_Courses Where course_id='$courseID' and student_id='$user_id'");
$result = mysql_query("SELECT `index`,name, DATE_FORMAT(due_date, '%d.%m.%y %H:%i') as due_date ,description FROM `Tasks` Where course_id = '$courseID' and DATEDIFF(NOW(), due_date) <= 2 order by due_date");
$resultCourse = mysql_query("SELECT name, lecturer, teacherEmail FROM `Courses` Where `index` = '$courseID'");
$resultFriends = mysql_query("SELECT fb_id FROM Users_Courses join Users on Users.index = Users_Courses.student_id Where course_id='$courseID' and student_id <> '$user_id'");
}
$friends = array();
if (mysql_num_rows($resultFriends) > 0) {
    while ($row = mysql_fetch_array($resultFriends)) {
        $friends[] = $row["fb_id"];
    }
}

if (mysql_num_rows($result) > 0) {

    $response["allTasks"] = array();

    while ($row = mysql_fetch_array($result)) {

        $task = array();
        $task["index"] = $row["index"];
        $task["name"] = $row["name"];
        $task["due_date"] = $row["due_date"];
        $task["description"] = $row["description"];
        array_push($response["allTasks"], $task);
    }

    $response["success"] = 1;
} else {

    $response["success"] = 0;
    $response["message"] = "No tasks found";
}

$response["is_user"] = ((mysql_num_rows($resultUser) > 0) || ($courseID == -1)) ? "1" : "0";
$response["friends"] = $friends;
$response["courseDetails"] = mysql_fetch_array($resultCourse);
echo json_encode($response);
?>