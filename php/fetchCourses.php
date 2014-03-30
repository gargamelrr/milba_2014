<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();


$user_id = $_SESSION["user_id"];
$user_school = $_SESSION["school"];
$friends_str = "";
foreach ($friend as $_SESSION["friends"]) {
        $friends_str .= ", " . $friend;
    }
$response["ddesdfbugg"] = $_SESSION["friends"];
//User is hard coded now.. look for PHP session later
$result_my = mysql_query("SELECT name, course_id FROM `Courses` , `Users_Courses` where Users_Courses.course_id = Courses.index and student_id=$user_id");

$result_else = mysql_query("SELECT name, course_id FROM Courses, User_Courses, Users where Users.index=User_Courses.student_id and Users_Courses.course_id = Courses.index and User_Courses.student_id in ($friends_str) and Users.school_id = $user_school ");
if (mysql_num_rows($result_my) > 0) {

    $response["userCourses"] = array();

    while ($row = mysql_fetch_array($result_my)) {

        $course = array();
        $course["name"] = $row["name"];
        $course["courseID"] = $row["course_id"];
        array_push($response["userCourses"], $course);
    }


    $response["success"] = 1;
} else {

    $response["success"] = 0;
    $response["message"] = "No tasks found";
}

$response["courses"] = array();

while ($row = mysql_fetch_array($result_else)) {

    $course = array();
    $course["name"] = $row["name"];
    $course["courseID"] = $row["index"];
    array_push($response["courses"], $course);
}

echo json_encode($response);
?>