<?php
/**
 * Created by PhpStorm.
 * User: zaza3
 * Date: 8/19/2018
 * Time: 8:11 AM
 */

require_once '../daemon/api/DBApi.php';
require_once '../daemon/api/StripeApi.php';


session_start();
$user_name = $_SESSION['user'];
$user_id = $_SESSION['user_id'];


if (!isset($user_name) || $user_name == '' || !isset($user_id) || $user_id == '')
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

$tab_name = "CAP Update";

?>


<!DOCTYPE html>
<html>
<?php include('./common/header.php'); ?>
<body>
<?php include('./dashboard_modal.php'); ?>
<?php include('./common/body_up.php'); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="crm_board">
            <div class="row crm_board_title">
                <div class="col-xs-10" style="padding-left: 0">CAP Update</div>
                <div class="col-xs-2 dashboard_sales_waiting" style="text-align:right;"></div>
            </div>
            <div class="alert alert-warning dashboard_sales_alert" role="alert" style="display:none"></div>
            <div class="row crm_board_row">
                <div class="col-xs-5">
                    <div class="input-daterange input-group" id="datepicker">
                        <span class="input-group-addon calendar_label">Date</span>
                        <input id="cap_date" type="text" class="input-sm form-control" name="cap"/>
                        <span class="input-group-addon calendar_label">From</span>
                        <input id="from_date" type="text" class="input-sm form-control" name="start"/>
                        <span class="input-group-addon calendar_label">To</span>
                        <input id="to_date" type="text" class="input-sm form-control" name="end"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm sales_search_button" type="button" style="width:100px"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search</button>
                        </span>
                    </div>
                </div>
            </div>
            <table class="table table-hover table_dashboard" style="margin-top:10px;">
                <thead>
                <tr>
                    <th>#</th>
                    <th>CRM/CAMPAIGN/AFFILIATE</th>
                    <th>GOAL</th>
                    <th>EDIT</th>
                    <th>CLEAR</th>
                </tr>
                </thead>
                <tbody class="table_dashboard_sales_body">
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('./common/body_down.php'); ?>
</body>
</html>
