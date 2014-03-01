<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

//User is hard coded now.. look for PHP session later
$result = mysql_query("SELECT name, course_id FROM `Courses` , `Users_Courses` where Users_Courses.course_id = Courses.index and student_id=1") or die(mysql_error());

if (mysql_num_rows($result) > 0) {
    
    $response["userCourses"] = array();

    while ($row = mysql_fetch_array($result)) {
        
        $course = array();
        $task["name"] = $row["name"];
        $task["courseID"] = $row["course_id"];
        array_push($response["userCourses"], $task);
        
    }

    $response["success"] = 1;

    echo json_encode($response);
    
} else {
    
    $response["success"] = 0;
    $response["message"] = "No tasks found";

    echo json_encode($response);
}
?>