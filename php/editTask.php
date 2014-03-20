<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];

$idToEdit = $_POST["id"];

$result = mysql_query("Select name, date(due_date) as date1, time(due_date) as time1, description, difficulty From Tasks Where Tasks.index=$idToEdit");

$response["tasks"] = array();

if(mysql_num_rows($result) == 1) {
    $row = mysql_fetch_array($result);
    
    $task = array();
    $task["index"] = $row["index"];
    $task["name"] = $row["name"];
    $task["date"] = $row["date1"];
    $task["time"] = $row["time1"];
    $task["description"] = $row["description"];
    
    array_push($response["tasks"], $task);
    $response["success"] = 1;
} else {
    $response["success"] = 0;
    $response["message"] = "No tasks found";
}

echo json_encode($response);

?>

