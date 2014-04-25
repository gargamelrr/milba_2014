<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$courseID = $_GET['courseID'];

if ($_GET["action"] == "join") {
    $result = mysql_query("insert into Users_Courses (student_id,course_id) values ($user_id,$courseID)");
    //update year according to stundets already registerd
    $resultUp = mysql_query("UPDATE Courses
set year = (SELECT ROUND(AVG(Users.year)) - YEAR(CURDATE()) FROM `Users` join Users_Courses where student_id=Users.index and course_id=$courseID)
 where Courses.index = $courseID");
} else if ($_GET["action"] == "leave") {
    $result = mysql_query("delete from Users_Courses where student_id=$user_id and course_id=$courseID");
}

$response["success"] = mysql_errno();
$response["message"] = mysql_error();
echo json_encode($response);
?>