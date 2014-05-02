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
$newTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Users.first_name, Users.last_name, Users.fb_id, Tasks.due_date From Tasks, Courses, Users_Courses,Users Where Tasks.Creator = Users.index and Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.created)) between 0 and 6 order by Tasks.modified desc");
$editedTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Users.first_name, Users.last_name, Users.fb_id, Tasks.due_date From Tasks, Courses, Users_Courses,Users Where Tasks.Creator = Users.index and Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.modified)) between 0 and 6 and Tasks.created != Tasks.modified order by Tasks.modified desc");
$deletedTasks = mysql_query("Select msg, Courses.name as cName From Events, Courses, Users_Courses Where student_id=$user_id and Users_Courses.course_id = Courses.index and Events.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Events.created)) between 0 and 6");

if (mysql_num_rows($newTasks) >= 0 || mysql_num_rows($editedTasks) >= 0 || mysql_num_rows($deletedTasks) >= 0) {
    $checkInput = 1;
    while ($row = mysql_fetch_array($newTasks)) {
        $new_date = date("m.d.Y", strtotime($row["due_date"]));
        $msg = " <div class='ui-grid-a'><div class='ui-block-a' style='width:37%'><img src='https://graph.facebook.com/" . $row["fb_id"] . "/picture?width=60&height=60'></div>"
                . "<div class='ui-block-b'><b>" .$row["first_name"] . " " . $row["last_name"] . '</b> added a new task to: <br/>' .
                '"' . $row["cName"] . '" <br/>' .
                ' Due: <strong>' . $new_date . '</strong></div></div>';

        array_push($response["allNoti"], $msg);
    }
    
    while ($row2 = mysql_fetch_array($editedTasks)) {
        $new_date = date("m.d.Y", strtotime($row2["due_date"]));
        $msg = " <div class='ui-grid-a'><div class='ui-block-a' style='width:37%'><img src='https://graph.facebook.com/" . $row2["fb_id"] . "/picture?width=60&height=60'></div>"
                . "<div class='ui-block-b'><b>" .$row2["first_name"] . " " . $row2["last_name"] . '</b> edited task in: <br/>' .
                '"' . $row2["cName"] . '" <br/>' .
                ' Due: <strong>' . $new_date . '</strong></div></div>';


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
