<?php

/*
 * this scripts run every night at 00:00 
 */

require_once __DIR__ . '/Global_Func.php';
require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

// select the higher day request for all users
$maxDaySql = mysql_query("select max(alertDays) as max from Users");
$maxDay = mysql_fetch_array($maxDaySql);
$maxDay = $maxDay["max"];

// for each task in the future
$tasksSql = mysql_query("SELECT * FROM `Tasks` WHERE datediff(due_date,NOW()) between 0 and $maxDay");

$courseID = "";
// go over all the tasks and get courses
while ($row = mysql_fetch_array($tasksSql)) {
    $courseID .= ", " . $row["course_id"];
}
$courseID = substr($courseID, 1);

$userIdSql = mysql_query("select Users.index,Users.alertDays, course_id,gcm from Users join Users_Courses on Users.index = Users_Courses.student_id and course_id in ($courseID)");
$usersID = mysql_fetch_array($userIdSql);
var_dump($usersID);

//
//
//foreach ($tasks as $key => $value) {
//    foreach ($usersID as $user => $settings) {
//        if ($settings["course_id"] == $value["course_id"]) {
//            // calc date and send if needed
//            if (date("Y-m-d") + $settings["alertDays"] == date("Y-m-d", $settings["due_time"])) {
//                //sendGCM(array($settings["gcm"]), "Dont forget to finish up your sheet in $courseName");
//            }
//        }
//    }
//}