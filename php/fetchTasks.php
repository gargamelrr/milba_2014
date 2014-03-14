<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$courseID = $_GET['courseID'];

$resultUser = mysql_query("SELECT * FROM Users_Courses Where course_id='$courseID' and student_id='$user_id'");
$result = mysql_query("SELECT `index`,name, due_date,description FROM `Tasks` Where course_id = '$courseID' and DATEDIFF(NOW(), due_date) <= 2 order by due_date");
$resultCourse = mysql_query("SELECT name, lecturer, teacherEmail FROM `Courses` Where `index` = '$courseID'");


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
$response["is_user"] = (mysql_num_rows($resultUser) > 0) ? "1" : "0";
$response["courseDetails"] = mysql_fetch_array($resultCourse);
echo json_encode($response);
?>