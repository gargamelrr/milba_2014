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

$result_users = mysql_query("select name,year,degree from Schools join Users on Users.school_id=Schools.index where Users.index=$user_id");
$row_user = mysql_fetch_array($result_users);

$result = mysql_query("select `index`,degree from Schools where name='" . $row_user["name"] . "' order by degree");
while ($row = mysql_fetch_array($result)) {
    $degree["name"] = $row["degree"];
    $degree["index"] = $row["index"];
    $degrees[] = $degree;
}

$response["schools"] = $schools;
$response["degrees"] = $degrees;
$response["success"] = 1;
$response["user_school"] = $row_user["name"];
$response["user_year"] = $row_user["year"];
$response["user_degree"] = $row_user["degree"];


echo json_encode($response);
?>