<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();
$courseID = $_GET['courseID'];
$result = mysql_query("SELECT name, due_date FROM `Tasks` Where course_id = '$courseID' order by due_date");

if (mysql_num_rows($result) > 0) {

    $response["allTasks"] = array();

    while ($row = mysql_fetch_array($result)) {

        $task = array();
        $task["name"] = $row["name"];
        $task["due_date"] = $row["due_date"];
        array_push($response["allTasks"], $task);
    }

    $response["success"] = 1;
} else {

    $response["success"] = 0;
    $response["message"] = "No tasks found";
}
echo json_encode($response);
?>