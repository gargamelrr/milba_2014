<?php



require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$user_school = $_SESSION["school"];
$year = $_SESSION["year"];
$response["userAlerts"] = array();

$result = mysql_query("Select sound, customAlert, alertDays, taskNoti, groupNoti From Users Where Users.index = $user_id");

if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_array($result)) {
        array_push($response["userAlerts"] , $row["sound"]);
        array_push($response["userAlerts"] , $row["customAlert"]);
        array_push($response["userAlerts"] , $row["alertDays"]);
        array_push($response["userAlerts"] , $row["taskNoti"]);
        array_push($response["userAlerts"] , $row["groupNoti"]);      
    }
$response["success"] = 1;
} else {
    $response["success"] = 0;
    $response["message"] = "No alerts found for the user ID";
}
echo json_encode($response);
?>