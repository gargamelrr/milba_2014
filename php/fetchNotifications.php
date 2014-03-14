<?php

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

session_start();

$user_id = $_SESSION["user_id"];
$NUMBER_OF_DAYS = 7;

$response["allTasks"] = array();
$checkInput = 0;

for($x = 0; $x < $NUMBER_OF_DAYS; $x++) {
            
    
    //Creating notifications for new tasks
    $dayTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Tasks.creator From Tasks, Courses Where Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.modified)) = $x");
   
    if(mysql_num_rows($dayTasks) > 0) {
        
        $response["allTasks"][$x] = array();
        while($row = mysql_fetch_array($dayTasks)) {
            $checkInput = 1;
            $msg =
                    '<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text">' .
                '<p class="ui-li-desc"> A new task ' . $row["tName"] . ' was added to:</p> <br>' .
                '<h2 class="ui-li-heading"> <strong> ' . $row["cName"] . '</strong></h2> <br>' .
                '<p class="ui-li-desc"> by: <strong>' . $row["creator"] . '</strong> </p>' .
                '</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>';
             array_push($response["allTasks"][$x], $msg);
        }
    }
}
    if($checkInput == 1) {
     $response["success"] = 1;
    } else {

        $response["success"] = 0;
        $response["message"] = "No tasks found";
    }
    echo json_encode($response);
  


?>
