<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
    </head>
    <body>
        <div data-role="page" class="ui-responsive-panel">
            <div data-role="header" data-theme="f">
                <h1>Search</h1>
                <a href="#nav-panel" data-icon="bars" data-iconpos="notext">Menu</a>
            </div>
            <?php
            include_once 'Menu.php';
            ?>
            <div data-role="content">
                <label for="search-basic">Search Groups:</label>
                <input type="search" name="search" id="search-basic" value="" placeholder="Type courses/teachers/friends"/>
                <div class="ui-grid-b">
                    <div class="ui-block-a"><div class="ui-bar ui-bar-e" style="height:60px">course 1</div></div>
                    <div class="ui-block-b"><div class="ui-bar ui-bar-e" style="height:60px">course 2</div></div>
                    <div class="ui-block-c"><div class="ui-bar ui-bar-e" style="height:60px">course 3</div></div>
                    <div class="ui-block-a"><div class="ui-bar ui-bar-e" style="height:60px">course 4</div></div>
                    <div class="ui-block-b"><div class="ui-bar ui-bar-e" style="height:60px">course 5</div></div>
                    <div class="ui-block-c"><div class="ui-bar ui-bar-e" style="height:60px">course 6</div></div>
                    <div class="ui-block-a"><div class="ui-bar ui-bar-e" style="height:60px">course 7</div></div>
                    <div class="ui-block-b"><div class="ui-bar ui-bar-e" style="height:60px">course 8</div></div>
                    <div class="ui-block-c"><div class="ui-bar ui-bar-e" style="height:60px">course 9</div></div>
                </div>
                <a href="index.php" data-role="button"  data-theme="d">Join</a>
            </div>
        </div>
    </body>
</html>
