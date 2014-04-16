<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

session_start();
$user_school = $_SESSION["school"];

if (isset($_POST["courseName"]) && isset($_POST["teacherName"])) {

   $response = array();
    $name = $_POST['courseName'];
    $teacher = $_POST['teacherName'];
    $email = $_POST['teacherMail'];
    $duration = $_POST['duration'];
    $year = date("Y");
    $date = date("Y-m-d");
    $user = $_SESSION["user_id"];

    $result = mysql_query("INSERT INTO `Courses`(`index`, `name`, `lecturer`, `admin`, `school_id`, `year`, `status`, `teacherEmail`,`created`,`duration`) "
            . "VALUES (NULL,'$name', '$teacher','$user','$user_school',$year,'active','$email','$date','$duration')");

// check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = mysql_error();
    }
} else {
    //error
    $response["success"] = 0;
    $response["message"] = "params werent set";
}

echo json_encode($response);
?>
