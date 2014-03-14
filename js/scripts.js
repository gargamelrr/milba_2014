var currentCoursePage = "";
var currentCourseId = "";
var currentTaskId = "";
function setCurrentCoursePage(val) {
    currentCoursePage = val;
}

function setCurrentCourseId(val) {
    currentCourseId = val;
}

function setCurrentTaskId(val) {
    currentTaskId = val;
}


//$(document).on("pageshow", "#home", function() {
//    $('.details').hide();
//    $('.ui-btn-text').click(function() {
//        $(this).find('.details').slideToggle(500);
//    });
//
//    $.ajax({
//        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/viewWeek.php',
//        method: 'POST',
//        cache: false,
//        data: {
//            //todo
//        },
//        success: function(data) {
//            var json = JSON.parse(data);
//            var day = 0;
//            $.each(json.data, function(i, val) {
//                $("#day" + day + " h3").append(json.data[i].date);
//                if (json.data[i].tasks.count > 0) {
//                    $.each(json.data[i].tasks.data, function(j, val) {
//                        if (j + 1 == json.data[i].tasks.count) {
//                            $("#day" + day + " p").append(json.data[i].tasks.data[j].course_name);
//                            $("#day" + day + " .details").append("<b><u><a href='GroupDetails.html' id='task" + i + "_" + j + "'>" + json.data[i].tasks.data[j].course_name + "</a></u><br/><br/>" +
//                                    json.data[i].tasks.data[j].task_name + "</b><br/><br/>" + json.data[i].tasks.data[j].due_date);
//                        } else {
//                            $("#day" + day + " p").append(json.data[i].tasks.data[j].course_name + ", ");
//                            $("#day" + day + " .details").append("<b><u><a href='GroupDetails.html' id='task" + i + "_" + j + "'>" + json.data[i].tasks.data[j].course_name + "</a></u><br/><br/>" +
//                                    json.data[i].tasks.data[j].task_name + "</b><br/><br/>" + json.data[i].tasks.data[j].due_date + "<br/><hr>");
//                        }
//                        if (json.data[i].tasks.count < 2) {
//                            $("#day" + day + " img").attr("src", "images/easy.png")
//                        } else {
//                            $("#day" + day + " img").attr("src", "images/hard.png")
//                        }
//
//                        $('#task' + i + "_" + j).click(function() {
//                            setCurrentCourseId(json.data[i].tasks.data[j].course_id);
//                        });
//                    });
//                } else {
//                    $("#day" + day + " img").attr("src", "images/fun.png");
//                }
//                day++;
//            });
//            if (json.status == 2) {
//                $("#empty").popup();
//                $("#empty").trigger("create");
//                $("#empty").popup("open");
//            }
//        },
//        error: function() {
//        }
//    });
//});


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
            }
            $('#name').text(json.courseDetails.name);
            $('#teac_name').text(json.courseDetails.lecturer);
            $('#email').text(json.courseDetails.teacherEmail);
            $('.details').hide();
            $('.btn-task').click(function() {
                $(this).find('.details').slideToggle(500);
            });
            if (currentTaskId != "") {
                $("#" + currentTaskId).find('.details').slideToggle(500);
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
                courseID: currentCourseId
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

    $("input[name='courses']").on("change", function() {
        if (this.value == 1) {
            $("#coursesMy").hide();
            $("#coursesSug").show();
        }
        else {
            $("#coursesSug").hide();
            $("#coursesMy").show();
        }
    });
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchCourses.php',
        method: 'POST',
        cache: false,
        success: function(data) {
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
    $('#coursesMy').hide();
});
function createCoursesButtons(coursesList, div) {
    var mainDiv = document.getElementById(div);
    mainDiv.innerHTML = "";
    console.log(mainDiv.id)
    for (var i = 0; i < coursesList.length; i++) {
        var subDiv = document.createElement("div");
        var a = document.createElement("a");
        a.id = "a";
        a.href = "GroupDetails.html";
        a.onclick = function() {
            setCurrentCourseId($(this).attr("data-ID"));
        };
        $(a).attr("data-ID", coursesList[i].courseID);
        a.innerHTML = coursesList[i].name;
        var br = document.createElement("br");
        var subsubDiv = document.createElement("div");
        subsubDiv.innerHTML = "Number of mutual friends - not implemented";
        a.appendChild(br);
        a.appendChild(subsubDiv);
        subDiv.appendChild(a);
        mainDiv.appendChild(subDiv);
    }

    $('#' + div + ' div').attr("class", "ui-block-a");
    $('#' + div + ' a').attr("class", "choosen");
    $('#' + div + ' a').attr("data-role", "button");
    $('#' + div + ' a').attr("data-theme", "b");
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
        cell1.appendChild(heading2);
        var dateTimeArray = (allTasks[i].due_date).split(" ");
        var date = reformatDate(dateTimeArray[0]);
        var time = dateTimeArray[1];
        var cellText2 = document.createTextNode("Due: " + date + " at " + time);
        var cell2Div = document.createElement("div");
        cell2Div.setAttribute("class", "details");
        cell2Div.appendChild(document.createTextNode(allTasks[i].description));
        cell1.appendChild(cellText2);
        cell1.appendChild(cell2Div);
        cell1Div.appendChild(cell1);
        row.appendChild(cell1Div);
        var cell2 = document.createElement("td");
        var editLink = document.createElement("a");
        editLink.href = "";
        editLink.innerHTML = "edit|";
        var delLink = document.createElement("a");
        delLink.href = "";
        delLink.innerHTML = "delete";
        cell2.appendChild(editLink);
        cell2.appendChild(delLink);
        row.appendChild(cell2);
        tblBody.appendChild(row);
    }
    table.appendChild(tblBody);
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
$(document).on("pageshow", "#profile", function() {
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
        method: 'POST',
        data: {
            dataUser: "yes"
        },
        success: function(data) {
            var json = JSON.parse(data);
            console.log(data);
            if (json.success == 1) {
                $("#name").text(json.user_name);
                parseProfile(json, true);
            }
        },
        error: function() {
            alert(data);
        }
    });
    $('select').on('change', function() {

        $.ajax({
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
            method: 'POST',
            data: {
                field: this.id,
                value: this.value,
                dataUser: "yes"
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    parseProfile(json, true);
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
});
function parseProfile(json, isYear) {
    var sel = $("#institue");
    sel.empty();
    for (var i = 0; i < json.schools.length; i++) {
        if (json.schools[i].name == json.user_school) {
            sel.append('<option value="' + json.schools[i].name + '" selected>' + json.schools[i].name + '</option>');
        } else {
            sel.append('<option value="' + json.schools[i].name + '">' + json.schools[i].name + '</option>');
        }
    }
    sel.selectmenu('refresh');
    var sel = $("#degree");
    sel.empty();
    for (var i = 0; i < json.degrees.length; i++) {
        if (json.degrees[i].name == json.user_degree) {
            sel.append('<option value="' + json.degrees[i].index + '" selected>' + json.degrees[i].name + '</option>');
        } else {
            sel.append('<option value="' + json.degrees[i].index + '">' + json.degrees[i].name + '</option>');
        }
    }
    sel.selectmenu('refresh');
    if (isYear) {
        var sel = $("#year");
        sel.empty();
        for (var i = 2020; i > 2010; i--) {
            if (i == json.user_year) {
                sel.append('<option value="' + i + '" selected>' + i + '</option>');
            } else {
                sel.append('<option value="' + i + '">' + i + '</option>');
            }
        }
        sel.selectmenu('refresh');
    }
}

$(document).on("pageshow", "#Notifications", function() {
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchNotifications.php',
        method: 'GET',
        success: function(data) {
            var json = JSON.parse(data);
            if (json.success == 1) {
                alert(json);
                buildNotifications(json.allTasks);
            }

        },
        error: function() {
            alert("fucka");
        }
    });
});
function buildNotifications(data) {

    for (var i = 6; i >= 0; i--) {

        var currentDayLi = document.getElementById("" + i);
        if (data[i] == null) {
            $('#' + currentDayLi).hide();
            continue;
        }

        //$('#'+currentDayLi).show();

        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate() - i);
        var currentDay = dayNumberToString(currentDate.getDay());
        var currentMonth = monthNumberToString(currentDate.getMonth());
        var currentMonthDay = currentDate.getDate();

        for (var j = 0; j <= data[i].length; j++) {
            var notification = document.createElement("li");
            $(notification).attr("data-corners", "false");
            $(notification).attr("data-shadow", "false");
            $(notification).attr("data-iconshadow", "true");
            $(notification).attr("data-wrapperels", "div");
            $(notification).attr("data-icon", "arrow-r");
            $(notification).attr("data-iconpos", "right");
            $(notification).attr("data-theme", "c");
            $(notification).attr("class", "ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c");
            var div1 = document.createElement("div");
            $(div1).attr("class", "ui-btn-inner ui-li");
            var div2 = document.createElement("div");
            $(div2).attr("class", "ui-btn-text");
            var currentYear = currentDate.getFullYear();
            var finalDateToDisplay = currentDay + ", " + currentMonth + " " + currentMonthDay + " " + currentYear;
            currentDayLi.innerHTML = finalDateToDisplay;
            for (var j = 0; j <= data[i].length; j++) {
//            var notification = document.createElement("li");
//            $(notification).attr("data-corners", "false");
//            $(notification).attr("data-shadow", "false");
//            $(notification).attr("data-iconshadow", "true");
//            $(notification).attr("data-wrapperels", "div");
//            $(notification).attr("data-icon", "arrow-r");
//            $(notification).attr("data-iconpos", "right");
//            $(notification).attr("data-theme", "c");
//            $(notification).attr("class", "ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-c");
//            var div1 = document.createElement("div");
//            $(div1).attr("class", "ui-btn-inner ui-li");
//            var div2 = document.createElement("div");
//            $(div2).attr("class", "ui-btn-text");
//            var p1 = document.createElement("p");
//            $(p1).attr("class","ui-li-desc");
//            p1.innerHTML = data[i][j];
//            var h2= document.createElement("h2");
//            $(p1).attr("class","ui-li-heading");
//            var strong = document.createElement("strong");
//            //strong.innerHTML = EXTRACT INFO FROM DATA
//            h2.appendChild(strong);
//            var p2 = document.createElement("p");
//            $(p2).attr("class","ui-li-desc");
//            //p2.innerHTML = EXTRACT INFO FROM DATA
//            div2.appendChild(p1);
//            div2.appendChild(h2);
//            div2.appendChild(p2);
//            var span =  document.createElement("span");
//            $(span).attr("class", "ui-icon ui-icon-arrow-r ui-icon-shadow");
//            span.innerHTML = "&nbsp;"
//            div1.appendChild(div2);
//            div1.appendChild(span);
//            notification.appendChild(div1);
//            currentDayLi.appendChild(notification);

                currentDayLi.innerHTML = data[i][j] + "<br>";
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

function monthNumberToString(number) {
    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return month[number];
}


$(document).on("pageshow", "#login", function() {
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
        method: 'POST',
        data: {
        },
        success: function(data) {
            var json = JSON.parse(data);
            if (json.success == 1) {
                parseProfile(json, true);
            }
        },
        error: function() {
            alert(data);
        }
    });
    $('select').on('change', function() {

        $.ajax({
            url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
            method: 'POST',
            data: {
                field: this.id,
                value: this.value
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json.success == 1) {
                    parseProfile(json, false);
                }
            },
            error: function() {
                alert(data.message);
            }
        });
    });
});