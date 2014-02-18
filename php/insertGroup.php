<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

if (isset($_POST["courseName"]) && isset($_POST["teacherName"])) {


    // array for JSON response

    $response = array();
    $name = $_POST['courseName'];
    $teacher = $_POST['teacherName'];
    $email = $_POST['teacherMail'];
    $year = date("Y");
    $user = "ronny";

    $result = mysql_query("INSERT INTO `Courses`(`index`, `name`, `lecturer`, `admin`, `school_id`, `year`, `status`, `teacherEmail`) "
            . "VALUES (NULL,'$name', '$teacher','$user',1,$year,'active','$email')");

    
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
    $response["message"] = mysql_error();
}

echo json_encode($response);
?>
