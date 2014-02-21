<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT;

//build dates array for the curent week
$i = 0;
while ($i < 8) {
    $day = mktime(0, 0, 0, date("m"), date("d") + $i, date("Y"));
    $date = date('Y-m-d', $day);
    if ($i == 0) {
        $text = "Today";
    } else if ($i == 1) {
        $text = "Tomorrow";
    } else {
        $text = date('l', strtotime($date));
    }
    $days[$date]["date"] = $text . " - " . date('d/m', $day);
    $days[$date]["tasks"]["count"] = 0;
    $i++;
}

$result = mysql_query("SELECT Tasks.`index`, `course_id`, Tasks.`name` as task_name, `due_date`, `description`,Courses.`name` as course_name FROM `Tasks` join Courses on Tasks.course_id=Courses.index where due_date between now() and '$date 23:59:00'");

while ($row = mysql_fetch_array($result)) {
    $date = explode(" ", $row["due_date"]);
    $days[$date[0]]["tasks"]["data"][] = $row;
    $days[$date[0]]["tasks"]["count"] = $days[$date[0]]["tasks"]["count"] + 1;
}

$response["count"] = mysql_num_rows($result);
$response["data"] = $days;
echo json_encode($response);
?>