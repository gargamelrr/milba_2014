<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/Global_Func.php';
require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();
$course_id = $_POST["course"];
$user_id = $_SESSION["user_id"];
$nameUser = $_SESSION["name"];

if (isset($_POST["friends"])) {

    $friends_str = "";
    foreach ($_POST["friends"] as $friend) {
        $friends_str .= ", " . $friend["id"];
    }
    $friends_str = substr($friends_str, 1);

    $resultGCM = mysql_query("select `index`,gcm from Users join Users_Courses on Users.index = Users_Courses.student_id "
            . " where fb_id in ($friends_str) and `index` not in (select `student_id` from Users_Courses where `index`=student_id  and course_id = $course_id)");

    while ($row = mysql_fetch_array($resultGCM)) {
        $regIDArray[] = $row["gcm"];
    }

    $courseNameSql = mysql_query("Select Courses.name From Courses Where Courses.index = $course_id");
    $courseName = mysql_fetch_array($courseNameSql);
    $courseName = $courseName["name"];
    //notify all users in course
    $message = "$nameUser invites you to share your sheets in $courseName";
    sendGCM($regIDArray, $message);

    //add notification for all
    foreach ($_POST["friends"] as $friend) {
        //check if already joined the course
        $fb_id = $friend["id"];
        $result = mysql_query("select `index` from Users join Users_Courses on Users.index = Users_Courses.student_id"
                . "where fb_id = $fb_id and Users_Courses.course_id = $course_id");
        if (mysql_num_rows($result) <= 0) {
            $insert = mysql_query("INSERT INTO `Invites`(`user_id`, `fb_id`, `course_id`, `msg`) VALUES ($user_id,$fb_id,$course_id,'$message')");
        }
    }
}

$response["success"] = 1;

echo json_encode($response);
