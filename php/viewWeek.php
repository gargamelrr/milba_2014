<?php

// include db connect class
require_once __DIR__ . '/db_connect.php';

// connecting to db
$db = new DB_CONNECT;
        
 //build dates array for the curent week
  

 $result = mysql_query("SELECT `index`, `course_id`, `name`, `due_date`, `description` FROM `Tasks`");

    if (mysql_num_rows($result) > 0) {
        $response["success"] = 1;
        $response["data"] = mysql_fetch_row($result);
    } else {
        $response["success"] = 0;
        $response["data"] = "";
    }
echo json_encode($response);
?>