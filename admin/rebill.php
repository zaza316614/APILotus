<?php
/**
 * Created by PhpStorm.
 * User: zaza3
 * Date: 8/6/2018
 * Time: 1:48 AM
 */

include '../daemon/api/DBApi.php';
include '../daemon/api/LLCrmApi.php';
require_once '../daemon/api/StripeApi.php';

session_start();
$user = $_SESSION['user'];
$userId = $_SESSION['user_id'];

if (!isset($user) || $user == '' || !isset($userId) || $userId == '')
{
    header("Location: ./login.php");
    return;
}
// session timeout
$now = time();
if ($now - $_SESSION['last_activity'] > 9660)
{
    session_unset();
    session_destroy();
    header("Location: ./login.php");
    return;
}
$_SESSION['last_activity'] = time();
if (isset($_COOKIE[session_name()]))
    setcookie(session_name(), $_COOKIE[session_name()], time() + 9660);
if ($_SESSION['last_activity'] - $_SESSION['created'] > 9660)
{
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}
session_write_close();
// check client ip
$dbApi = DBApi::getInstance();
if(!$dbApi->checkClientIp())
{
    header("Location: ./blockip_alert.php");
    return;
}

// check subscription for payment
include ('./common/check_payment.php');

$user_name = $user;
$crmList = $dbApi->getAllActiveTrialCrmsByAccountId($userId);

$tab_name = "Rebill";


?>


<!DOCTYPE html>
<html>
<?php include('./common/header.php'); ?>
<body>
<?php include('./common/body_up.php'); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="crm_board" style="margin-bottom: 15px;">
            <div class="row crm_board_title">
                <div class="col-xs-10" style="padding-left: 0">Rebill Report</div>
                <div class="col-xs-2 rebill_waiting" style="text-align:right;"></div>
            </div>
            <div class="alert alert-warning rebill_alert" role="alert" style="display:none"></div>
            <div class="row crm_board_row">
                <div class="col-xs-7">
                    <div class="input-daterange input-group" id="datepicker">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle crm_toggle_button" data-toggle="dropdown" aria-expanded="false" style="min-width:160px">
                                <?php
                                if ($crmList != null && count($crmList) > 0)
                                    echo $crmList[0][1].' ';
                                else
                                    echo 'None CRM ';
                                ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu crm_dropdown_menu" role="menu">
                                <?php
                                if ($crmList != null) {
                                    for ($i = 0; $i < count($crmList); $i++)
                                        echo '<li><a href="#" id="'.$crmList[$i][0].'" class="crm_dropdown_list">'.$crmList[$i][1].'</a></li>';
                                }
                                ?>
                            </ul>
                        </span>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle date_toggle_button" data-toggle="dropdown" aria-expanded="false" style="width:160px; border-radius: 0">
                                Full Week <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu date_dropdown_menu" role="menu">
                                <li><a href="#" id="date_fullweek">Full Week</a></li>
                                <li><a href="#" id="date_custom">Custom</a></li>
                            </ul>
                        </span>
                        <span id="rebill_date_label" class="input-group-addon calendar_label">Date</span>
                        <input id="rebill_date" type="text" class="input-sm form-control" name="rebill"/>
                        <span class="input-group-addon calendar_label">From</span>
                        <input id="from_date" type="text" class="input-sm form-control" name="start"/>
                        <span class="input-group-addon calendar_label">To</span>
                        <input id="to_date" type="text" class="input-sm form-control" name="end"/>

                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm rebill_search_button" type="button" style="width:100px"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search</button>
                        </span>
                    </div>
                </div>
                <div class="col-xs-5" style="text-align:right; height:30px; padding-right: 30px;">
                    <button class="btn btn-default btn-sm btn_export_quick" type="button" style="width:120px;margin-right: 10px"><span class="glyphicon glyphicon-export" aria-hidden="true"></span>&nbsp;&nbsp;Quick Export</button>
                    <button class="btn btn-default btn-sm btn_export_full" type="button" style="width:120px;margin-right: 10px"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;&nbsp;Full Export</button>
                </div>
            </div>

            <table class="table table-hover table_rebill" style="margin-top:10px;">
                <thead class="table_rebill_head">
                <tr>
                    <th id="id_head_sign" rowspan="2" style="vertical-align:middle"></th>
                    <th rowspan="2" style="vertical-align:middle"></th>
                    <?php
                        if ($crmList != null)
                            echo '<th id="id_campaign_title" rowspan="2" style="vertical-align:middle">'.$crmList[0][1].'</th>';
                        else
                            echo '<th id="id_campaign_title" rowspan="2" style="vertical-align:middle">No Campaign</th>';
                    ?>
                    <th>Sales</th>
                    <th>Approval Rate</th>
                    <th>Rebill %</th>
                </tr>
                </thead>
                <tbody class="table_rebill_body">
                </tbody>
            </table>

        </div>
    </div>
</div>
<?php include('./common/body_down.php'); ?>
</body>
</html>
