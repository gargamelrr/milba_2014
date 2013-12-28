<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>New Group</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
        <link rel="stylesheet" href="css/style.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
    </head>
    <body>
        <div data-role="page" class="ui-responsive-panel">
            <div data-role="header" data-theme="f">
                <a href="#nav-panel" data-icon="bars" data-iconpos="notext">Menu</a><br/>
            </div><!-- /header -->
            <?php
            include_once 'Menu.php';
            ?>
            <div data-role="content">
                <form method="post">
                    <div id="newTask">
                        <div id="groups">
                           <a href="" data-role="button"  id="custom-btn" data-inline="true" data-theme="b"> Search </a>
                           <a href="" data-role="button"  id="custom-btn" data-inline="true">מילאב</a>
                           <a href="" data-role="button"  id="custom-btn" data-inline="true">אלגברה ליניארית </a>
                           <a href="" data-role="button"  id="custom-btn" data-inline="true">דיני עסקים</a>
                        </div>
                        
                        <br/>
                        <label>Task Name:</label>
                        <input type="text" name="taskName" id="taskName"/> <br>
                        <label>Due Date:</label>
                        <input type="date" name="taskDate" id="taskDate"/> <br>
                        <label>Due Time:</label>
                        <input type="time" name= "taskTime" id="taskTime"/> <br>
                        <label>Task Description:</label>
                        <textarea cols="40" rows="80" name="taskDetails" id="taskDetails"> </textarea> <br> 
                        <fieldset data-role="controlgroup" data-type="horizontal" >
                            <legend>Choose Task Difficulty:</legend>
                            <input type="radio" name="radio-difficulty" id="easy" value="easy-1" checked="checked"/> 
                            <label for="easy">Easy</label>
                            <input type="radio" name="radio-difficulty" id="hard" value="hard-1"/>
                            <label for="hard">Hard</label>
                        </fieldset><br> 
                        <a href="" data-role="button"  data-theme="a" data-icon="gear" > Choose from Dropbox: </a><br/>
                    </div>
                    <input type="submit" value="Publish"/>
                </form>

            </div>
        </div>
    </body>
</html>
