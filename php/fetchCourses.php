<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$response["debug"] = $_SESSION["user_id"];

$user_id = $_SESSION["user_id"];
$user_school = $_SESSION["school"];

$friends_str = "";
foreach ($_SESSION["friends"] as $friend) {
    $friends_str .= ", " . $friend;
}
$friends_str = substr($friends_str, 1);

//$response["ddesdfbugg"] = "SELECT name, course_id FROM Courses, Users_Courses, Users where Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Users.school_id = $user_school ";
$result_my = mysql_query("SELECT name, course_id FROM `Courses` , `Users_Courses` where Users_Courses.course_id = Courses.index and student_id=$user_id");

$result_friends = mysql_query("SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name");
$result_else = mysql_query("SELECT name, Courses.index FROM Courses, Users_Courses where Users_Courses.student_id not in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name");

if (mysql_num_rows($result_my) > 0) {

    $response["userCourses"] = array();

    while ($row = mysql_fetch_array($result_my)) {
        $course = array();
        $course["name"] = $row["name"];
        $course["courseID"] = $row["course_id"];
        $course["count"] = 0;
        array_push($response["userCourses"], $course);
    }

    $response["success"] = 1;
} else {

    $response["success"] = 0;
    $response["message"] = "No tasks found";
}

$response["courses"] = array();
$courses_id = array();
while ($row = mysql_fetch_array($result_friends)) {

    $course = array();
    $course["name"] = $row["name"];
    $course["courseID"] = $row["index"];
    $course["count"] = $row["num_fri"];
    array_push($response["courses"], $course);

    $courses_id[$row["index"]] = 1;
}

while ($row = mysql_fetch_array($result_else)) {

    if (!array_key_exists($row["index"], $courses_id)) {
        $course = array();
        $course["name"] = $row["name"];
        $course["courseID"] = $row["index"];
        $course["count"] = 0;
        array_push($response["courses"], $course);
    }
}
$response["debug"] = "SELECT name, Courses.index FROM Courses, Users_Courses where Users_Courses.student_id not in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name";
echo json_encode($response);
?>