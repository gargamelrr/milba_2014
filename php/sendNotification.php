<?php

/*
 * this scripts run every night at 00:00 
 */

require_once __DIR__ . '/Global_Func.php';

// select the higher day request for all users
$maxDaySql = mysql_query("select max(alertDays) from users");
$maxDay = mysql_fetch_field($result)[0];

// for each task in the future
$tasksSql = mysql_query("SELECT * FROM `Tasks` WHERE  due_date => (NOW() + $maxDay days)");
$tasks = mysql_fetch_array($tasksSql);

$courseID = "";
// go over all the tasks and get courses
foreach ($tasks as $key => $value) {
    $courseID .= ", " . $value["course_id"];
}
$courseID = substr($courseID, 1);
$userIdSql = mysql_query("select Users.index,Users.alertDays, course_id,gcm from Users join Users_Courses on Users.index = Users_Courses.student_id and course_id in ($courseID)");
$usersID = mysql_fetch_array($userIdSql);


foreach ($tasks as $key => $value) {
    foreach ($usersID as $user => $settings) {
        if ($settings["course_id"] == $value["course_id"]) {
            // calc date and send if needed
            if (date("Y-m-d") + $settings["alertDays"] == date("Y-m-d", $settings["due_time"])) {
                sendGCM(array($settings["gcm"]), "Dont forget to finish up your sheet in $courseName");
            }
        }
    }
}