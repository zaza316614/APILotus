<?php

require_once '../api/DBApi.php';


session_start();
if (!isset($_SESSION['ap_user_id']) || $_SESSION['ap_user_id'] == "")
{
    session_write_close();
    echo 'no_cookie';
    return;
}
session_write_close();


$subDomain = $_GET['sub_domain'];
$crmID = $_GET['crm_id'];
$apiPassword = $_GET['api_password'];

$dbApi = DBApi::getInstance();
$dbApi->setSubDomain($subDomain);

if ($dbApi->getSubDomain() == '')
{
    echo 'no_cookie';
    return;
}

$ret = $dbApi->updateCrmApiPassword($crmID, $apiPassword);
if ($ret)
	echo 'success';
else
	echo 'error';

?>