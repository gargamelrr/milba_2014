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
    $tasks[] = $row;
}
$courseID = substr($courseID, 1);

$userIdSql = mysql_query("select Users.index,Users.alertDays, course_id,gcm,Courses.name as course_name,customAlert from Users join Users_Courses on Users.index = Users_Courses.student_id join Courses on Courses.index = Users_Courses.course_id where course_id in ($courseID)");

foreach ($tasks as $task) {
    mysql_data_seek($userIdSql, 0);
    $dateTask = strtotime(date("Y-m-d", strtotime($task["due_date"]))) . "\n";
    while ($row = mysql_fetch_array($userIdSql)) {
        if ($row["customAlert"] == 1 && $row["course_id"] == $task["course_id"]) {
            // calc date and send if needed
            $datePlusAlert = strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " +" . $row["alertDays"] . "day") . "\n";
            if ($datePlusAlert == $dateTask) {
                sendGCM(array($row["gcm"]), "Don't forget to finish up your sheet in " . $row["course_name"]);
                echo "send notification to " . $row["index"] . " for " . $row["course_name"];
            }
        }
    }
}