

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

$(document).on("pagebeforeshow", function(){
    if(name == "" ){
        setFb_id("100008051852593");
        setName("Coral Landa");
        
    }
   $("#profile span").text(name);
   $('#nav-panel').trigger('create');
});