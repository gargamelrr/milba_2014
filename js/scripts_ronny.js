var gcm = "";
var datePv = "";

var easyWeekText = ["\"Sheets Happen\"", "\"Confront your Sheet!\"", "\"Dont be careless about your sheets\"",
    "\"Will the sheets hit the fan?\"", "\"Let's beat the sheets out of it!\""];
var hardWeekText = ["\"common, pull your sheets together!\"",
    "\"Uh oh… Mountains of sheets ahead\"", "\"Sheets Happen\"",
    "\"Pretty Sheety situation here, huh?\"", "\"It seems  you're in a pile of sheets\"",
    "\"Will the sheets hit the fan?\""];

function setGCM(val) {
    gcm = val;
}

function setDatePv(val) {
    datePv = val;
}

function addPvTask(date) {
    setCurrentCourseId("-1");
    setDatePv(date);
    $.mobile.changePage("GroupDetails.html");
}

$(document).on("pageshow", "#home", function() {

    var open = false;
    $('.details').hide();
    $('#days .ui-btn-text').click(function() {
        open = !open;
        if (open) {
            $(".count_tasks").hide();
            $("#days img").hide();
            $(".date").hide();
            $(".ul-de").hide();
            $(".paper").hide();
            $("#days li").addClass("closed").addClass("withoutPaper");
            $(this).find(".task_date_full").show();
            $(this).find(".task_num_tasks").show();
            $(this).parent().parent().removeClass("closed");
        } else {
            $(".count_tasks").show();
            $("#days img").show();
            $(".date").show();
            $(".ul-de").show();
            $(".paper").show();
            $("#days .task_num_tasks").hide();
            $("#days .task_date_full").hide();
            $("#days li").removeClass("closed").removeClass("withoutPaper");
        }
        $(this).find('.details').slideToggle(100);
        $("#days").trigger("create");
    });
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/viewWeek.php',
        method: 'POST',
        cache: false,
        data: {
            //todo
        },
        success: function(data) {
            //alert(data);
            var json = JSON.parse(data);
            var day = 0;
            var easy = 0;
            $("#days .task_num_tasks").hide();
            $("#days .task_date_full").hide();
            $.each(json.data, function(i, val) {
                $("#day" + day + " h3").text("");
                $("#day" + day + " .task_date").append(json.data[i].date);
                $("#day" + day + " .details").text("");
                $("#day" + day + " .count_tasks").text("");
                $("#day" + day + " ul").text("");
                if (json.data[i].tasks.count > 0) {
                    var output = "";
                    $.each(json.data[i].tasks.data, function(j, val) {
                        if (j < 2) {
                            var name = (json.data[i].tasks.data[j].course_name.length > 10) ? json.data[i].tasks.data[j].course_name.substring(0, 14) + "..." : json.data[i].tasks.data[j].course_name;
                            output += '<li>' + name + '</li>';
                        }
                        //$("#day" + day + " .details").append("<div class='homeTask'><div class='taskHeader'><b><a href='GroupDetails.html' id='task" + i + "_" + j + "'>" + json.data[i].tasks.data[j].course_name + "</a></b></div>" +
                        //        json.data[i].tasks.data[j].task_name + "<br/><br/>" + json.data[i].tasks.data[j].time + "</div>");
                        $("#day" + day + " .details").append("<div class='homeTask' id='task" + i + "_" + j + "'><div class='taskHeader'><b>" + json.data[i].tasks.data[j].course_name + "</b></div>" +
                                json.data[i].tasks.data[j].task_name + "<br/><br/>" + json.data[i].tasks.data[j].time + "</div>");

                        $('#task' + i + "_" + j).click(function() {
                            setCurrentCourseId(json.data[i].tasks.data[j].course_id);
                            setCurrentTaskId(json.data[i].tasks.data[j].index);
                            $.mobile.changePage('GroupDetails.html');
                        });
                        if (json.data[i].tasks.data[j].difficulty == 0) {
                            easy++;
                        } else {
                            easy = easy + 3;
                        }
                    });
                    if (day == 0) {
                        if (json.data[i].tasks.count < 2) {
                            $("#day" + day + " img").attr("src", "images/easy_0.png");
                        } else {
                            $("#day" + day + " img").attr("src", "images/hard_0.png");
                        }
                    }
                    else {
                        if (json.data[i].tasks.count < 2) {
                            $("#day" + day + " img").attr("src", "images/easy_2.png");
                            $("#day" + day + " .left").css("color", "#28a9d4");
                            $("#day" + day + " ul").css("color", "#28a9d4");
                            $("#days li:nth-child(" + (day + 1) + ")").css("border-top", "1px solid #28a9d4");
                            $("#days li:nth-child(" + (day + 1) + ")").css("background-image", "url(images/paper_2.png)");

                        } else {
                            $("#day" + day + " img").attr("src", "images/hard_3.png");
                            $("#day" + day + " .left").css("color", "#0f7192");
                            $("#day" + day + " ul").css("color", "#0f7192");
                            $("#days li:nth-child(" + (day + 1) + ")").css("border-top", "1px solid #0f7192");
                            $("#days li:nth-child(" + (day + 1) + ")").css("background-image", "url(images/paper_3.png)");

                        }
                    }
                    if (day == 1) {
                        if (json.data[i].tasks.count < 2) {
                            $("#days li:nth-child(" + (day + 1) + ")").css("background-image", "url(images/paper_second_2.png)");
                        } else {
                            $("#days li:nth-child(" + (day + 1) + ")").css("background-image", "url(images/paper_second_3.png)");
                        }
                    }
                    $("#day" + day + " .count_tasks").append(json.data[i].tasks.count);
                    $("#day" + day + "-ul").append(output).trigger('create');
                } else {
                    $("#day" + day + " .count_tasks").append("Free");
                    $("#day" + day + " img").attr("src", "images/fun.png");
                }
                if (day == 1) {
                    $("#days li:nth-child(" + (day + 1) + ") .paper").addClass("paper-blue");

                }
                var d = new Date(json.data[i].date_full);
                var curr_date = d.getDate();
                var curr_month = d.getMonth();
                curr_month++;
                var curr_year = d.getFullYear();
                $("#day" + day + " .task_date_full").append("<b>" + curr_date + "." + curr_month + "." + curr_year + "</b>");
                $("#day" + day + " .task_num_tasks").append("<b>" + json.data[i].tasks.count + "</b> Tasks");
                $("#day" + day + " .details").append("<div id='pvTask'><input type='button' value='Add a personal Sheet'" + 'onclick="addPvTask(\'' + json.data[i].date_full + '\')"/></div>');
                $("#day" + day + " .details").trigger("create");
                day++;
            });
            if (easy < 3) {
                $('#title').text(easyWeekText[Math.floor(Math.random() * easyWeekText.length)]);
            } else {
                $('#title').text(hardWeekText[Math.floor(Math.random() * hardWeekText.length)]);
            }
            if (json.status == 2) {
                $("#empty").popup();
                $("#empty").trigger("create");
                $("#empty").popup("open");
            }
        },
        error: function() {
        }
    });
});
$(document).on("pageshow", "#profilePage", function() {
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
                $("#nameProfile").text(json.user_name);
                parseProfile(json, true);
                $('#imgProfile').attr('src', 'https://graph.facebook.com/' + localStorage.getItem("ID") + '/picture?width=120&height=120');
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
        if (json.schools[i].name == "Institute") {
            sel.append('<option value="" disabled selected>' + json.schools[i].name + '</option>');
        }
        else if (json.schools[i].name == json.user_school) {
            sel.append('<option value="' + json.schools[i].name + '" selected>' + json.schools[i].name + '</option>');
        } else {
            sel.append('<option value="' + json.schools[i].name + '">' + json.schools[i].name + '</option>');
        }
    }
    sel.selectmenu('refresh');
    var sel = $("#degree");
    sel.empty();
    for (var i = 0; i < json.degrees.length; i++) {
        if (json.degrees[i].name == "School") {
            sel.append('<option value="" disabled selected>' + json.degrees[i].name + '</option>');
        }
        else if (json.degrees[i].name == json.user_degree) {
            sel.append('<option value="' + json.degrees[i].index + '" selected>' + json.degrees[i].name + '</option>');
        } else {
            sel.append('<option value="' + json.degrees[i].index + '">' + json.degrees[i].name + '</option>');
        }
    }
    sel.selectmenu('refresh');
    if (isYear) {
        var sel = $("#year");
        sel.empty();
        var year = new Date().getFullYear();
        sel.append('<option value="" disabled selected>' + "Year of Start" + '</option>');
        for (var i = 2009; i <= year; i++) {
            if (i == json.user_year) {
                sel.append('<option value="' + i + '" selected>' + i + '</option>');
            } else {
                sel.append('<option value="' + i + '">' + i + '</option>');
            }
        }
        sel.selectmenu('refresh');
    }
}

$(document).on("pageshow", "#login", function() {
    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
        method: 'POST',
        data: {
        },
        success: function(data) {
            //alert(data);
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
        if (this.id == "institue") {
            $.ajax({
                url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/userProfile.php',
                method: 'POST',
                data: {
                    field: this.id,
                    value: this.value,
                    school: $("#institue").val()
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
        }
    });
});
$(document).on("pageshow", function(e) {

    if (e.target.id != "login") {
        if (localStorage.getItem("Name") == null) {
            localStorage.setItem('Name', "Tom Blotman");
            localStorage.setItem('ID', "1055121807");
        }
        $.mobile.activePage.find("#nameFB").text(localStorage.getItem("Name"));
        $.mobile.activePage.find('#imgFB').attr('src', 'https://graph.facebook.com/' + localStorage.getItem("ID") + '/picture');
        $.mobile.activePage.find('#nav-panel').trigger('create');

    }
});
// result contains any message sent from the plugin call
function successHandler(result) {
    console.log('result = ' + result);
}

// result contains any error description text returned from the plugin call
function errorHandler(error) {
    console.log('error = ' + error);
}

function tokenHandler(result) {
// Your iOS push server needs to know the token before it can push to this device
// here is where you might want to send it the token for later use.
    console.log('device token = ' + result);
}

function onNotificationGCM(e) {
    switch (e.event)
    {
        case 'registered':
            if (e.regid.length > 0)
            {
// Your GCM push server needs to know the regID before it can push to this device
// here is where you might want to send it the regID for later use.
//alert("regID = " + e.regid);
                setGCM(e.regid);
            }
            break;
        case 'message':
            alert(e.payload.message);
            break;
        case 'error':
            alert('GCM error = ' + JSON.stringify(e));
            break;
        default:
            alert('An unknown GCM event has occurred');
            break;
    }
}

function backKeyDown() {
//navigator.app.exitApp();
}

document.addEventListener('deviceready', function() {
    document.addEventListener("backbutton", backKeyDown, true);
    try {
        if ((typeof cordova == 'undefined') && (typeof Cordova == 'undefined'))
            console.log('Cordova variable does not exist. Check that you have included cordova.js correctly');
        if (typeof CDV == 'undefined')
            console.log('CDV variable does not exist. Check that you have included cdv-plugin-fb-connect.js correctly');
        if (typeof FB == 'undefined')
            console.log('FB variable does not exist. Check that you have included the Facebook JS SDK file.');
        FB.init({appId: "691029124265305",
            nativeInterface: CDV.FB,
            useCachedDialogs: false,
            status: true, // check login status
            cookie: true});
        // document.getElementById('data').innerHTML = "";


        FB.Event.subscribe('auth.login', function(response) {
            console.log('auth.login event');
        });
        FB.Event.subscribe('auth.logout', function(response) {
            console.log('auth.logout event');
        });
        FB.Event.subscribe('auth.sessionChange', function(response) {
            console.log('auth.sessionChange event');
        });
        FB.Event.subscribe('auth.statusChange', function(response) {
            console.log('auth.statusChange event');
        });
        var pushNotification;
        pushNotification = window.plugins.pushNotification;
        if (device.platform == 'android' || device.platform == 'Android')
        {
            pushNotification.register(
                    successHandler,
                    errorHandler, {
                        "senderID": "965749566309",
                        "ecb": "onNotificationGCM"
                    });
        }
        else
        {
            pushNotification.register(
                    tokenHandler,
                    errorHandler, {
                        "badge": "true",
                        "sound": "true",
                        "alert": "true",
                        "ecb": "onNotificationAPN"
                    });
        }
    } catch (e) {
        console.log("e1");
    }
    console.log('Device is ready! ');
}, false);


function invite() {
    FB.ui({method: 'apprequests',
        message: 'lalala'
    },
    function(response) {
        alert(response);
    }
    );
}