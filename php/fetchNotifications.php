<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$NUMBER_OF_DAYS = 7;

$checkInput = 0;
$response["allNoti"] = array();

//Creating notifications for new tasks
$newTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Tasks.creator, Tasks.due_date From Tasks, Courses, Users_Courses Where Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.created)) between 0 and 6 order by Tasks.modified desc");
$editedTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Tasks.creator, Tasks.due_date From Tasks, Courses, Users_Courses Where Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.modified)) between 0 and 6 and Tasks.created != Tasks.modified order by Tasks.modified desc");
$deletedTasks = mysql_query("Select msg, Courses.name as cName From Events, Courses, Users_Courses Where student_id=$user_id and Users_Courses.course_id = Courses.index and Events.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Events.created)) between 0 and 6");

if (mysql_num_rows($newTasks) >= 0 || mysql_num_rows($editedTasks) >= 0 || mysql_num_rows($deletedTasks) >= 0) {
    $checkInput = 1;
    while ($row = mysql_fetch_array($newTasks)) {
        $new_date = date("m-d-Y H:i:s", strtotime($row["due_date"]));
        $msg = '<p class="ui-li-desc"> A new task ' . $row["tName"] . ' was added to:</p> ' .
                '<h2 class="ui-li-heading"> <strong> ' . $row["cName"] . '</strong></h2> ' .
                '<p class="ui-li-desc"> by: <strong>' . $row["creator"] . '</strong> </p>' .
                '<p class="ui-li-desc"> Due: <strong>' . $new_date . '</strong> </p>';

        array_push($response["allNoti"], $msg);
    }
    while ($row2 = mysql_fetch_array($editedTasks)) {
        $new_date = date("m-d-Y H:i:s", strtotime($row2["due_date"]));
        $msg = '<p class="ui-li-desc">The task ' . $row2["tName"] . ' was edited in:</p> ' .
                '<h2 class="ui-li-heading"> <strong> ' . $row2["cName"] . '</strong></h2> ' .
                '<p class="ui-li-desc"> by: <strong>' . $row2["creator"] . '</strong> </p>' .
                '<p class="ui-li-desc"> Due: <strong>' . $new_date . '</strong> </p>';

        array_push($response["allNoti"], $msg);
    }

    while ($row3 = mysql_fetch_array($deletedTasks)) {
        $msg = '<p class="ui-li-desc">' . $row3["msg"] . ' From: </p> ' .
                '<h2 class="ui-li-heading"> <strong> ' . $row3["cName"] . '</strong></h2> ';

        array_push($response["allNoti"], $msg);
    }
}


if ($checkInput == 1) {
    $response["success"] = 1;
} else {

    $response["success"] = 0;
    $response["message"] = "No notifications found";
}
echo str_replace('\\/', '/', json_encode($response));
?>
