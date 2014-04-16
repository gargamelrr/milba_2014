<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();
session_start();

if (isset($_POST["keyword"])) {
    $user_id = $_SESSION["user_id"];
    $user_school = $_SESSION["school"];

    $friends_str = "";
    foreach ($_SESSION["friends"] as $friend) {
    $friends_str .= ", " . $friend;
    }
    $friends_str = substr($friends_str, 1);
    
    $response = array();
    $keyword = $_POST['keyword'];
   
    $resultMutualCourses = mysql_query("SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where Courses.name LIKE '%$keyword%' and Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name");
    $resultNoMutualCourses = mysql_query("SELECT name, Courses.index FROM Courses, Users_Courses where Courses.name LIKE '%$keyword%' and Users_Courses.student_id not in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");

    $resultTeacher;
    $resultFriends;

// check if row inserted or not
    if (mysql_num_rows($resultMutualCourses)>0 || 
            mysql_num_rows($resultTeachers)>0 || 
            mysql_num_rows($resultFriends) >0 ||
            mysql_num_rows($resultNoMutualCourses) >0) {
        
        $response["searchResult"] = array();
        while ($row = mysql_fetch_array($resultMutualCourses)) {
            $course = array();
            $course["name"] = $row["name"];
            $course["courseID"] = $row["index"];
            $course["count"] = $row["num_fri"];
            array_push($response["searchResult"], $course);
        }
        
        while ($row = mysql_fetch_array($resultNoMutualCourses)) {
            $course = array();
            $course["name"] = $row["name"];
            $course["courseID"] = $row["index"];
            $course["count"] = 0;
            array_push($response["searchResult"], $course);
        }
        
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "Search successfully completed.";       
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = mysql_error();
    }
} else {
    //error
    $response["success"] = 0;
    $response["message"] = "keyword wasnt set";
}

echo json_encode($response);
?>
