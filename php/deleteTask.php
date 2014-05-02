
<?php

$response = array();

require_once __DIR__ . '/db_connect.php';
 
$db = new DB_CONNECT(); 

session_start();

$user_id = $_SESSION["user_id"];

$idToEdit = $_POST["id"];
$nameUsre = $_SESSION["name"];

$courseID = $_POST["courseID"];

if($courseID == -1) {
    $result = mysql_query("Select name, date(due_date) as date1, time(due_date) as time1, description, difficulty From Users_PrivateTasks Where Users_PrivateTasks.index=$idToEdit");
} else {
$result = mysql_query("Select name, course_id, date(due_date) as date1, time(due_date) as time1, description, difficulty From Tasks Where Tasks.index=$idToEdit");
$courseName = mysql_query("Select Courses.name From Tasks, Courses Where Tasks.index=$idToEdit and Courses.index = Tasks.course_id");
}
if(mysql_num_rows($result) == 1) {

    $row = mysql_fetch_array($result);

//This was buggy have no idea why this is here
//    $index = $row["index"];
//    $name = $row["name"];
//    $date = $row["date1"];
//    $time = $row["time1"];
//    $courseID = $row["course_id"];
//    $description = $row["description"];
//    
    
    if($courseID == -1) {
        $deletion = mysql_query("Delete From Users_PrivateTasks Where Users_PrivateTasks.index = $idToEdit");
    } else {
        $deletion = mysql_query("Delete From Tasks Where Tasks.index = $idToEdit");
    }

if (mysql_affected_rows() > 0) {
    $insertion = mysql_query("Insert into Events(`course_id`, `msg`) Values ('$courseID', 'The task $name was deleted by $nameUsre')");
    if (mysql_affected_rows() > 0) {
        $response["success"] = 1;
    } else {
    $response["success"] = 0;
    $response["message"] = "Could not delete task 0";
} 


} else {
    $response["success"] = 0;
    $response["message"] = "Could not delete task 1";
}
    
    
}

echo json_encode($response);

?>


