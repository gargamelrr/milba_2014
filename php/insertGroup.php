<?php
/*
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
 * */

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT();

if (isset($_POST["courseName"]) && isset($_POST["teacherName"])) {
   
    
    // array for JSON response

    $response = array();
    $name = $_POST['courseName'];
    $teacher = $_POST['teacherName'];
    
    $result = mysql_query("INSERT INTO `Courses`(`name`, `lecturer`) VALUES('$name', '$teacher')");


// check if row inserted or not
    if ($result) {
        // successfully updated
        $response["success"] = 1;
        $response["message"] = "User successfully updated.";

        // echoing JSON response
        echo json_encode($response);
    } else {
        //error
        $response["success"] = 0;
        $response["message"] = "Error";

        // echo no users JSON
        echo json_encode($response);
    }
     
     
} else {
    //error
    $response["success"] = 3;
    $response["message"] = "Error";

    // echo no users JSON
    echo json_encode($response);
}
?>
