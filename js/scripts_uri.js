var currentCoursePage = "";
var currentCourseId = "";
var currentTaskId = "";
var fb_id = -1;
var name = "";

function setCurrentCoursePage(val) {
    currentCoursePage = val;
}

function setCurrentCourseId(val) {
    currentCourseId = val;
}

function setCurrentTaskId(val) {
    currentTaskId = val;
}

function setName(val) {
    name = val;
}

function setFb_id(val) {
    fb_id = val;
}

$(document).on("pageshow", "#courseDetails", function() {
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchTasks.php',
        method: 'GET',
        data: {
            courseID: currentCourseId
        },
        success: function(data) {
            var json = JSON.parse(data);

            if (json.success == 1) {
                buildTasks(json.allTasks);
            }

            if (json.is_user == "1") {
                $("#join-course").hide();
                $("#addTask").show();
                $("#leave-course").show();
            } else {
                $("#leave-course").hide();
                $("#addTask").hide();
                $('.actions').hide();
            }
            $('#name').text(json.courseDetails.name);
            var temp = json.courseDetails.lecturer == null ? "" : json.courseDetails.lecturer;
            $('#teac_name').text(temp);
            temp = json.courseDetails.teacherEmail == null ? "" : " / " + json.courseDetails.teacherEmail;
            $('#email').text(temp);
            $('.details').hide();
            $('.btn-task').click(function() {
                $(this).find('.details').slideToggle(200);
            });
            if (currentTaskId != "") {
                $("#" + currentTaskId).find('.details').slideToggle(200);
                setCurrentTaskId("");
            }
        },
        error: function() {
            alert("error");
        }
    });
    $('#submit').click(function() {
        $.ajax({
            //add full 
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/insertTask.php',
            method: 'POST',
            data: {
                taskName: $("#taskName").val(),
                date1: $("#date1").val(),
                taskTime: $("#taskTime").val(),
                radiodifficulty: $("input:radio[name=radiodifficulty]:checked").val(),
                taskdetails: $("#taskdetails").val(),
                courseID: currentCourseId,
                taskID: $("#editFlag").val()
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    window.location.href = "index.html";
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
    $('#join-course').click(function() {
        $.ajax({
            //add full 
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/coursesActions.php',
            method: 'GET',
            data: {
                action: "join",
                courseID: currentCourseId
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 0) {
                    window.location.href = "index.html";
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
    $('#leavenow').click(function() {
        $.ajax({
            //add full 
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/coursesActions.php',
            method: 'GET',
            data: {
                action: "leave",
                courseID: currentCourseId
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 0) {
                    window.location.href = "index.html";
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
});

$(document).on("pageshow", "#courses", function() {

    if (currentCoursePage == "join") {
        $('#but-my').removeClass('ui-btn-active').trigger('create');
        $('#but-sug').addClass('ui-btn-active').trigger('create');
        $("#coursesMy").hide();
        $("#coursesSug").show();
    } else {
        $('#but-sug').removeClass('ui-btn-active').trigger('create');
        $('#but-my').addClass('ui-btn-active').trigger('create');
        $("#coursesSug").hide();
        $("#coursesMy").show();
    }

    $("#but-sug").click(function() {
        $('#but-my').removeClass('ui-btn-active').trigger('create');
        $('#but-sug').addClass('ui-btn-active').trigger('create');
        $("#coursesMy").hide();
        $("#coursesSug").show();
        $("#newGroup").hide();
    });

    $("#but-my").click(function() {
        $('#but-sug').removeClass('ui-btn-active').trigger('create');
        $('#but-my').addClass('ui-btn-active').trigger('create');
        $("#coursesSug").hide();
        $("#coursesMy").show();
        $("#newGroup").hide();
    });


    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchCourses.php',
        method: 'POST',
        success: function(data) {
            //alert(data);
            var json = JSON.parse(data);
            if (json.success == 1) {
                createCoursesButtons(json.userCourses, "coursesMy");
            }
            createCoursesButtons(json.courses, "coursesSug");
        },
        error: function() {
            console.log("error");
        }
    });

    //$("#coursesMy").hide();
});



function createCoursesButtons(coursesList, div) {

    var mainDiv = document.getElementById(div);
    mainDiv.innerHTML = "";
    console.log(mainDiv.id);
    // button for create new group
    var subDiv = document.createElement("div");
    subDiv.className = "ui-block-a";
    var courseNewdiv = document.createElement("a");
    courseNewdiv.id = "newCourseBtn";
    courseNewdiv.className = "newCourse";
    courseNewdiv.href = "";
    courseNewdiv.onclick = function() {
        $("#coursesSug").hide();
        $("#coursesMy").hide();
        $("#newGroup").show();
    };
    courseNewdiv.innerHTML = "Add a Course";

    subDiv.appendChild(courseNewdiv);
    mainDiv.appendChild(subDiv);

    for (var i = 0; i < coursesList.length; i++) {
        var subDiv = document.createElement("div");
        var courseDiv = document.createElement("a");
        if (i % 2 == 0) {
            subDiv.className = "ui-block-b";
        } else {
            subDiv.className = "ui-block-a";
        }
        courseDiv.href = "GroupDetails.html";
        courseDiv.id = "a" + coursesList[i].courseID;
        courseDiv.onclick = function() {
            setCurrentCourseId($(this).attr("data-ID"));
        };
        $(courseDiv).attr("data-ID", coursesList[i].courseID);
        courseDiv.innerHTML = "<b>" + coursesList[i].name + "</b>";

        var br = document.createElement("br");
        var subsubDiv = document.createElement("div");
        subsubDiv.innerHTML = "<b>" + coursesList[i].count + "</b> Friends";
        subsubDiv.className = "count_friend";

        if (i % 3 == 0) {
            courseDiv.className = "courseBtn courseBtnRed";
        } else if (i % 3 == 1) {
            courseDiv.className = "courseBtn courseBtnBlue";
        } else {
            courseDiv.className = "courseBtn courseBtnPurple";
        }
        courseDiv.appendChild(br);
        courseDiv.appendChild(subsubDiv);
        subDiv.appendChild(courseDiv);
        mainDiv.appendChild(subDiv);
    }

    $('#' + div + ' a').attr("data-role", "button");
    $("#a div").attr("class", "count_friend");
    $('#' + div).trigger('create');
}

function reformatDate(date)
{
    dateArray = date.split("-");
    return dateArray[2] + "/" + dateArray[1] + "/" + dateArray[0].substring(2);
}

function buildTasks(allTasks) {
    var table = document.getElementById("tasks-table-custom");
    table.innerHTML = "";
    var tblBody = document.createElement("tbody");
    for (var i = 0; i < allTasks.length; i++) {
        var row = document.createElement("tr");
        var cell1Div = document.createElement("div");
        cell1Div.setAttribute("class", "btn-task");
        cell1Div.setAttribute("id", allTasks[i].index);
        var cell1 = document.createElement("td");
        var heading2 = document.createElement("h3");
        heading2.innerHTML = allTasks[i].name;
        var dateTimeArray = (allTasks[i].due_date).split(" ");
        var date = reformatDate(dateTimeArray[0]);
        var time = dateTimeArray[1];
        var cellText2 = document.createTextNode(date + " " + time);
        var cell2Div = document.createElement("div");
        //cell2Div.setAttribute("class", "details");
        cell2Div.appendChild(document.createTextNode(allTasks[i].description));
        cell1.appendChild(cellText2);
        cell1.appendChild(heading2);
        cell1.appendChild(cell2Div);
        cell1Div.appendChild(cell1);

        var cell3Div = document.createElement("div");
        cell3Div.setAttribute("class", "actions");

        var cell2 = document.createElement("td");
        var editLink = document.createElement("a");
        $(editLink).attr('id', allTasks[i].index);
        editLink.href = "";
        editLink.innerHTML = "edit | ";

        $(editLink).click(function() {

            $("#editFlag").val($(this).attr('id'));
            $(document).load();
            $.ajax({
                url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/editTask.php',
                method: 'POST',
                data: {
                    id: $(this).attr('id')
                },
                success: function(data) {
                    var json = JSON.parse(data)

                    if (json.success == 1) {
                        fillUpFieldsAfterEdit(json.tasks[0]);
                    } else {
                        alert("error parsing json");
                    }
                },
                error: function() {
                    alert.data(data.message);
                }
            });
        });

        var delLink = document.createElement("a");
        delLink.href = "";
        delLink.innerHTML = "delete";

        $(delLink).click(function() {
            $(document).load();
            $.ajax({
                url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/deleteTask.php',
                method: 'POST',
                data: {
                    id: $(this).prev().attr('id')
                },
                success: function(data) {
                    var json = JSON.parse(data)
                    if (json.success == 1) {
                        window.location.href = "index.html";
                    } else {
                        alert("error parsing json");

                    }
                },
                error: function() {
                    alert.data(data.message);
                }
            });
        });

        cell2.appendChild(editLink);
        cell2.appendChild(delLink);
        cell3Div.appendChild(cell2);
        cell1Div.appendChild(cell3Div);
        row.appendChild(cell1Div);
        tblBody.appendChild(row);
    }
    table.appendChild(tblBody);
}

function fillUpFieldsAfterEdit(json) {

    $("#taskName").val(json.name);
    $("#date1").val(json.date);
    $("#taskTime").val(json.time);
    $("#taskdetails").text(json.description);
    if (json.difficulty == 1) {
        $('#hard').attr('checked', 'checked');
    }
    else {
        $('#easy').attr('checked', 'checked');
    }
    $("input[name='radiodifficulty']").checkboxradio("refresh");
}

$(document).on("pageshow", "#addCourse", function() {
    $('#submit').click(function() {
        $.ajax({
            //add full 
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/insertGroup.php',
            method: 'POST',
            data: {
                courseName: $("#courseName").val(),
                teacherName: $("#teacherName").val(),
                duration: $("input:radio[name=dur]:checked").val(),
                teacherMail: $("#teacherEmail").val()
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    window.location.href = "index.html";
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
});


$(document).on("pageshow", "#Notifications", function() {
    //uriFriends();
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchNotifications.php',
        method: 'GET',
        success: function(data) {
            var json = JSON.parse(data);

            if (json.success == 1) {

                buildNotifications(json.allTasks);
            } else {

            }
        },
        error: function() {
            alert("error");
        }
    });
});

function buildNotifications(data) {
    for (var i = 0; i <= 6; i++) {

        var currentDayLi = document.getElementById("" + i);

        if (data[i] == null || data[i].length == 0) {
            $(currentDayLi).hide();
            continue;
        }

        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate() - i);
        var currentDay = dayNumberToString(currentDate.getDay());
        var currentMonth = monthNumberToString(currentDate.getMonth());
        var currentMonthDay = currentDate.getDate();
        var currentYear = currentDate.getFullYear();
        var finalDateToDisplay = currentDay + ", " + currentMonth + " " + currentMonthDay + " " + currentYear;
        currentDayLi.innerHTML = finalDateToDisplay;

        for (var j = 0; j <= data[i].length; j++) {
            $("#noNotifications").hide();
            for (var j = 0; j < data[i].length; j++) {
                currentDayLi.innerHTML += data[i][j];
            }
        }
    }
}

//<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c"><div class="ui-btn-inner ui-li"><div class="ui-btn-text">
//                                <p class="ui-li-desc">A new task added to</p>
//                                <h2 class="ui-li-heading"><strong>אוטומטים</strong></h2>
//                                <p class="ui-li-desc">Due Date is 1.9.13</p>
//                            </div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>

function dayNumberToString(number)
{
    var weekday = new Array(7);
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";
    return weekday[number];
}

function uriFriends() {
    FB.api('/me/friends', function(response) {
        if (response.data) {
            $.each(response.data, function(index, friend) {
                alert(friend.name + ' has id:' + friend.id);
            });
        } else {
            alert("Erroooor!");
        }
    });
}

function monthNumberToString(number)
{
    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return month[number];
}
