jQuery(document).ready(function(t) {
    function show_alert(e) {
        t(".affiliation_alert").html(e);
        t(".affiliation_alert").fadeIn(1e3, function() {
            t(".affiliation_alert").fadeOut(3e3);
        });
    }
    function show_waiting(status) {
        status ? t(".affiliation_waiting").html(loading_gif) : t(".affiliation_waiting").html("");
    }
    function set_dates() {
        t("#from_date").prop("disabled", true);
        t("#to_date").prop("disabled", true);
        var cur_date = new Date;
        var formatted_date = format_date(cur_date.getFullYear(), cur_date.getMonth() + 1, cur_date.getDate());

        var r = cur_date.getDate() + 1;
        0 == cur_date.getDay() ? r -= 7 : r -= cur_date.getDay();
        cur_date.setDate(r);
        from_date = format_date(cur_date.getFullYear(), cur_date.getMonth() + 1, cur_date.getDate());
        r = cur_date.getDate() + 6;
        cur_date.setDate(r);
        to_date = format_date(cur_date.getFullYear(), cur_date.getMonth() + 1, cur_date.getDate());

        t("#affiliation_date").val(formatted_date);
        t("#from_date").val(from_date);
        t("#to_date").val(to_date);
    }
    function format_date(year, month, date) {
        if (month < 10) month = "0" + month;
        if (date < 10) date = "0" + date;
        return month + "/" + date + "/" + year;
    }
    function get_affiliation_list() {
        show_waiting(true);
        t(".table_affiliation_body").html("");
        if ("" == t("#from_date").val()) {
            show_alert("Please select FROM DATE.");
        }
        else if ("" == t("#to_date").val()) {
            show_alert("Please select TO DATE.");
        }
        else {
            t.ajax({
                type: "GET",
                url: "../daemon/ajax_admin/setting_affiliation_list.php",
                data: {
                    from_date: t("#from_date").val(),
                    to_date: t("#to_date").val()
                },
                success: function(e) {
                    show_waiting(false);
                    if ("no_cookie" === e)
                        return void (window.location.href = "../../admin/login.php");

                    results = jQuery.parseJSON(e);
                    var html = "";
                    for (var i = 0; i < results.length; i++) {
                        var affiliate = results[i];
                        html += '<tr>';
                        html += '<td><span class="payment_badge payment_badge_blue">' + affiliate[0][1] + '</span>';
                        html += '<button type="button" class="btn btn-link btn-sm btn_affiliation_edit" id="aedit_' + i + '" data-toggle="modal" data-target="#affiliation_edit_modal"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>&nbsp;Edit</button>';
                        html += '<button type="button" class="btn btn-link btn-sm btn_affiliation_delete" id="adelete_' + i + '" data-toggle="modal" data-target="#affiliation_delete_modal"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true" style="color: #ffa5a5"></span>&nbsp;Delete</button></td>';
                        if (null == affiliate[0][2])
                            html += '<td></td>';
                        else
                            html += '<td>' + affiliate[0][2] + '</td>';
                        html += '<td></td>';
                        html += '<td></td>';
                        html += '<td><button type="button" class="btn btn-link btn-sm btn_affiliation_goal_edit" id="gedit_' + i + '" data-toggle="modal" data-target="#affiliation_goal_edit_modal"><span class="glyphicon glyphicon-list" aria-hidden="true"></span>&nbsp;Edit Goal</button></td>';
                        html += '</tr>';
                        for (var j = 0; j < affiliate[1].length; j++) {
                            var offer = affiliate[1][j];
                            html += '<tr>';
                            html += "<td></td>";
                            html += "<td></td>";
                            html += "<td>" + offer[3] + "</td>";
                            html += "<td>" + offer[4] + "</td>";
                            html += "<td>" + offer[1] + "</td>";
                            html += '</tr>';
                        }
                    }
                    t(".table_affiliation_body").html(html);
                },
                failure: function(t) {
                    show_waiting(false);
                    show_alert("Cannot load affiliate goal information.");
                }
            })
        }
    }

    function add_affiliate() {
        show_waiting(true);
        $.ajax({
            type : "GET",
            url : "../daemon/ajax_admin/setting_affiliation_add.php",
            data : {
                name: $(".add_affiliation_name").val(),
                afid : $(".add_affiliation_afid").val()
            },
            success : function(status) {
                show_waiting(false);
                if ("error" == status)
                    show_alert("Affiliate cannot be added.");
                else if ("no_cookie" == status)
                    window.location.href = "../../admin/login.php";
                else if ("success" == status)
                    get_affiliation_list();
            },
            failure : function(e) {
                show_waiting(false);
                show_alert("Affiliate cannot be added.");
            }
        });
    }

    function edit_affiliate() {
        show_waiting(true);
        $.ajax({
            type : "GET",
            url : "../daemon/ajax_admin/setting_affiliation_edit.php",
            data : {
                affiliate_id: affiliate_id,
                name: $(".edit_affiliation_name").val(),
                afid : $(".edit_affiliation_afid").val()
            },
            success : function(status) {
                show_waiting(false);
                if ("error" == status)
                    show_alert("Affiliate cannot be changed.");
                else if ("no_cookie" == status)
                    window.location.href = "../../admin/login.php";
                else if ("success" == status)
                    get_affiliation_list();
            },
            failure : function(e) {
                show_waiting(false);
                show_alert("Affiliate cannot be changed.");
            }
        });
    }

    function delete_affiliate() {
        show_waiting(true);
        $.ajax({
            type : "GET",
            url : "../daemon/ajax_admin/setting_affiliation_delete.php",
            data : {
                affiliate_id: affiliate_id
            },
            success : function(status) {
                show_waiting(false);
                if ("error" == status)
                    show_alert("Affiliate cannot be deleted.");
                else if ("no_cookie" == status)
                    window.location.href = "../../admin/login.php";
                else if ("success" == status)
                    get_affiliation_list();
            },
            failure : function(e) {
                show_waiting(false);
                show_alert("Affiliate cannot be deleted.");
            }
        });
    }

    function edit_affiliate_goal(affiliation_goal_id, offer_ids, offer_goals) {
        show_waiting(true);
        $.ajax({
            type : "GET",
            url : "../daemon/ajax_admin/setting_affiliation_edit_goal.php",
            data : {
                affiliate_id: affiliation_goal_id,
                offer_ids: offer_ids,
                offer_goals: offer_goals,
                from_date: $("#from_date").val(),
                to_date : $("#to_date").val()
            },
            success : function(status) {
                show_waiting(false);
                if ("error" == status)
                    show_alert("Affiliate goals cannot be changed.");
                else if ("no_cookie" == status)
                    window.location.href = "../../admin/login.php";
                else if ("success" == status)
                    get_affiliation_list();
            },
            failure : function(e) {
                show_waiting(false);
                show_alert("Affiliate goals cannot be changed.");
            }
        });
    }

    t("#from_date").datepicker({});
    t("#to_date").datepicker({});
    t("#affiliation_date").datepicker({});
    t("#affiliation_date").change(function () {
        var cur_date = new Date($(this).val());
        var r = cur_date.getDate() + 1;
        0 == cur_date.getDay() ? r -= 7 : r -= cur_date.getDay();
        cur_date.setDate(r);
        from_date = format_date(cur_date.getFullYear(), cur_date.getMonth() + 1, cur_date.getDate());
        r = cur_date.getDate() + 6;
        cur_date.setDate(r);
        to_date = format_date(cur_date.getFullYear(), cur_date.getMonth() + 1, cur_date.getDate());
        t("#from_date").val(from_date);
        t("#to_date").val(to_date);
    });
    t(".affiliation_search_button").click(function() {
        get_affiliation_list();
    });

    $(".btn_affiliation_add").click(function() {
        $(".add_affiliation_name").val("");
        $(".add_affiliation_afid").val("");
    });
    $(".modal_btn_affiliation_add").click(function() {
        $("#affiliation_add_modal").modal("toggle");
        add_affiliate();
    });

    $(document).on("click", ".btn_affiliation_edit", function() {
        var id = $(this).prop("id").substring(6);
        affiliate_id = results[id][0][0];
        $(".edit_affiliation_name").val(results[id][0][1]);
        $(".edit_affiliation_afid").val(results[id][0][2]);
    });
    $(".modal_btn_affiliation_edit").click(function() {
        $("#affiliation_edit_modal").modal("toggle");
        edit_affiliate();
    });

    $(document).on("click", ".btn_affiliation_delete", function(a) {
        var id = $(this).prop("id").substring(8);
        affiliate_id = results[id][0][0];
    });
    $(".modal_btn_affiliation_delete").click(function() {
        $("#affiliation_delete_modal").modal("toggle");
        delete_affiliate();
    });

    $(document).on("click", ".btn_affiliation_goal_edit", function() {
        var html = "";
        affiliate_goal_id = $(this).prop("id").substring(6);
        var affiliation = results[affiliate_goal_id];
        $(".affiliation_goal_edit_body_label").html(affiliation[0][1]);
        for (var i = 0; i < affiliation[1].length; i++) {
            var offer = affiliation[1][i];
            html += '<div class="row" style="margin-bottom: 5px;">';
            html += '<div class="col-xs-4 modal_input_label">' + offer[3] + "</div>";
            html += '<div class="col-xs-8"><input type="text" id="editgoal_' + offer[2] + '" class="form-control input-sm edit_goals" value="' + offer[1] + '"></div>';
            html += "</div>";
        }
        t(".affiliation_goal_edit_body").html(html);
    });
    $(".modal_btn_affiliation_goal_edit").click(function() {
        var ids = "";
        var goals = "";
        t(".edit_goals").each(function() {
            "" != ids && (ids += ",");
            "" != goals && (goals += ",");
            ids += t(this).prop("id").substring(9);
            "" == t(this).val() ? goals += "0" : goals += t(this).val();
        });
        $("#affiliation_goal_edit_modal").modal("toggle");
        edit_affiliate_goal(results[affiliate_goal_id][0][0], ids, goals);
    });

    var loading_gif = '<img src="../images/loading.gif" style="width:22px;height:22px;">';
    var minus_sign = '<span class="glyphicon glyphicon-minus-sign" aria-hidden="true" style="color: #ffa5a5"></span>';
    var triangle_sign = '<span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true" style="color: #ffa5a5"></span>';
    var from_date = "";
    var to_date = "";
    var results = null;
    var affiliate_id = -1;
    var affiliate_goal_id = -1;

    set_dates();
    get_affiliation_list();
});
