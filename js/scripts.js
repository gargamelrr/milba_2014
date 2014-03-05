function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
    return "";
}

var currentCoursePage = "";

function setCurrentCoursePage(val) {
    currentCoursePage = val;
}

$(document).on( "pageshow", "#courses", function() {
    console.log(currentCoursePage);

    $(function() {
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
    });


    $.ajax({
        url: 'http://ronnyuri.milab.idc.ac.il/milab_2014/php/fetchCourses.php',
        method: 'POST',
        cache: false,
        success: function(data) {
            console.log(data);
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
    //$('#coursesMy').hide();
});



function createCoursesButtons(coursesList, div) {
    var mainDiv = document.getElementById(div);
    mainDiv.innerHTML = "";
    console.log(mainDiv.id+"::"+mainDiv.innerHTML);
    for (var i = 0; i < coursesList.length; i++) {
        var subDiv = document.createElement("div");
        var a = document.createElement("a");
        a.id = "a";
        a.href = "GroupDetails.html" + "?courseID=" + coursesList[i].courseID;
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
    console.log(mainDiv.id+"::"+mainDiv.innerHTML);
    
    $('#' + div + ' div').attr("class", "ui-block-a");
    $('#' + div + ' a').attr("class", "choosen");
    $('#' + div + ' a').attr("data-role", "button");
    $('#' + div + ' a').attr("data-theme", "b");
    $("#a div").attr("class", "count_friend");
    $('#' + div).trigger('create');
    
}






