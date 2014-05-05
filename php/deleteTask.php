
<?php

$response = array();

require_once __DIR__ . '/db_connect.php';
require_once __DIR__ . '/Global_Func.php';


$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];

$idDel = $_POST["id"];
$nameUser = $_SESSION["name"];

$courseID = $_POST["courseID"];

if ($courseID == -1) {
    $result = mysql_query("Select name From Users_PrivateTasks Where Users_PrivateTasks.index=$idDel");
} else {
    $result = mysql_query("Select name From Tasks Where Tasks.index=$idDel");
    $courseNameSql = mysql_query("Select Courses.name From Tasks, Courses Where Tasks.index=$idDel and Courses.index = Tasks.course_id");
    $courseName = mysql_fetch_array($courseNameSql);
    $courseName = $courseName["name"];
}
if (mysql_num_rows($result) == 1) {

    $row = mysql_fetch_array($result);

    if ($courseID == -1) {
        $deletion = mysql_query("Delete From Users_PrivateTasks Where Users_PrivateTasks.index = $idDel");
    } else {
        $deletion = mysql_query("Delete From Tasks Where Tasks.index = $idDel");
    }

    if ($deletion) {
        $message = "$nameUser just deleted a Sheet in $courseName";

        $insertion = mysql_query("Insert into Events(`student_id`,`course_id`, `msg`) Values ('$user_id','$courseID', '$message')");
        //notify all users in course
        //bring all registerID
        $resultGCM = mysql_query("select gcm from Users join Users_Courses on Users.index = Users_Courses.student_id where course_id = $courseID and student_id <> $user_id");
        while ($row = mysql_fetch_array($resultGCM)) {
            if($row["gcm"] != "")
            $regIDArray[] = $row["gcm"];
        }
        sendGCM($regIDArray, $message);

        if ($insertion) {
            $response["success"] = 1;
        } else {
            $response["success"] = 0;
            $response["message"] = "Could not delete task $idDel";
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "Could not delete task $idDel";
    }
}

echo json_encode($response);
?>


