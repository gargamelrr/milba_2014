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
    $newTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Tasks.creator, Tasks.due_date From Tasks, Courses, Users_Courses Where Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.created)) = $x");
    $editedTasks = mysql_query("Select Tasks.name as tName, Courses.name as cName, Tasks.creator, Tasks.due_date From Tasks, Courses, Users_Courses Where Users_Courses.course_id = Courses.index and student_id=$user_id and Tasks.course_id = Courses.index and DATEDIFF(CURDATE(), DATE(Tasks.modified)) = $x and Tasks.created != Tasks.modified");

    if(mysql_num_rows($newTasks) >= 0 || mysql_num_rows($editedTasks) >= 0 ) {

        $response["allTasks"][$x] = array();
        
        $checkInput = 1;
        while($row = mysql_fetch_array($newTasks)) {
            
            $new_date = date( "m-d-Y H:i:s", strtotime( $row["due_date"] ) );
           
            $msg = 
                '<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text">' .
                '<p class="ui-li-desc"> A new task ' . $row["tName"] . ' was added to:</p> ' .
                '<h2 class="ui-li-heading"> <strong> ' . $row["cName"] . '</strong></h2> ' .
                '<p class="ui-li-desc"> by: <strong>' . $row["creator"] . '</strong> </p>' .
                     '<p class="ui-li-desc"> Due: <strong>' . $new_date . '</strong> </p>' .
                '</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>';
            
            
             array_push($response["allTasks"][$x], $msg);
             
            while($row2 = mysql_fetch_array($editedTasks)) {
                $new_date = date( "m-d-Y H:i:s", strtotime( $row2["due_date"] ) );
                $msg = 
                '<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text">' .
                '<p class="ui-li-desc">The task ' . $row2["tName"] . ' was edited in:</p> ' .
                '<h2 class="ui-li-heading"> <strong> ' . $row2["cName"] . '</strong></h2> ' .
                '<p class="ui-li-desc"> by: <strong>' . $row2["creator"] . '</strong> </p>' .
                        '<p class="ui-li-desc"> Due: <strong>' . $new_date . '</strong> </p>' .
                '</div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>';
    
                array_push($response["allTasks"][$x], $msg);
            }
        }
    }
}
    if($checkInput == 1) {
     $response["success"] = 1;
    } else {

        $response["success"] = 0;
        $response["message"] = "No notifications found";
    }
    echo str_replace('\\/', '/', json_encode($response));


?>
