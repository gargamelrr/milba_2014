<?php

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];

$result = mysql_query("select distinct name from Schools order by name");

while ($row = mysql_fetch_array($result)) {
    $school["name"] = $row["name"];
    $schools[] = $school;
}

$result = mysql_query("select name,year from Schools join Users on Users.school_id=Schools.index where Users.index=$user_id");
$row = mysql_fetch_array($result);

$response["schools"] = $schools;
$response["success"] = 1;
$response["user_school"] = $row["name"];
$response["user_year"] = $row["year"];


echo json_encode($response);
?>