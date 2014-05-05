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
    if ($friends_str != "") {
        $friends_str = substr($friends_str, 1);
    } else {
        $friends_str = "0";
    }
    
    $response = array();
    $keyword = $_POST['keyword'];

    $resultMutualCourses = mysql_query("SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where Courses.name LIKE '%$keyword%' and Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name");
    $resultNoMutualCourses = mysql_query("SELECT name, Courses.index FROM Courses, Users_Courses where Courses.name LIKE '%$keyword%' and Users_Courses.student_id not in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");
    $resultMutualTeacher = mysql_query("SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where Courses.lecturer LIKE '%$keyword%' and Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");
    $resultNoMutualTeacher = mysql_query("SELECT name, Courses.index FROM Courses, Users_Courses where Courses.lecturer LIKE '%$keyword%' and Users_Courses.student_id not in ($friends_str) and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");
    $resultFriends = mysql_query("SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where (Users.first_name LIKE '%$keyword%' or Users.last_name LIKE '%$keyword%') and Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");;
    $resultNoMutualFriends =  mysql_query("SELECT name, Courses.index FROM Courses, Users_Courses, Users where Users.index=Users_Courses.student_id and (Users.first_name LIKE '%$keyword%' or Users.last_name LIKE '%$keyword%') and Users_Courses.student_id not in ($friends_str) and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name ");

    $response["debug1"] = "SELECT name, Courses.index,count(*) as num_fri FROM Courses, Users_Courses, Users where Courses.name LIKE '%$keyword%' and Users.index=Users_Courses.student_id and Users_Courses.course_id = Courses.index and Users_Courses.student_id in ($friends_str) and Courses.school_id = $user_school and Courses.index not in (select course_id from Users_Courses where student_id = $user_id) group by name";

    if (mysql_num_rows($resultMutualCourses) > 0 ||
            mysql_num_rows($resultMutualTeacher) > 0 ||
            mysql_num_rows($resultNoMutualTeacher) > 0 ||
            mysql_num_rows($resultFriends) > 0 ||
            mysql_num_rows($resultNoMutualCourses) > 0) {

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

        while ($row = mysql_fetch_array($resultMutualTeacher)) {
            $course = array();
            $course["name"] = $row["name"];
            $course["courseID"] = $row["index"];
            $course["count"] = $row["num_fri"];
            array_push($response["searchResult"], $course);
        }

        while ($row = mysql_fetch_array($resultNoMutualTeacher)) {
            $course = array();
            $course["name"] = $row["name"];
            $course["courseID"] = $row["index"];
            $course["count"] = 0;
            array_push($response["searchResult"], $course);
        }

        while ($row = mysql_fetch_array($resultFriends)) {
            $course = array();
            $course["name"] = $row["name"];
            $course["courseID"] = $row["index"];
            $course["count"] = $row["num_fri"];
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
