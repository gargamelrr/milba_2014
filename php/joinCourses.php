<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$courseID = $_GET['courseID'];

$result = mysql_query("insert into Users_Courses (student_id,course_id) values ($user_id,$courseID)");

$response["success"] = 1;
echo json_encode($response);
?>