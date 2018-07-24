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

$dbApi = DBApi::getInstance();
$loginHistory = $dbApi->getLoginHistory();

if ($loginHistory != null)
{
	echo json_encode(array('success', $loginHistory));
	return;
}

echo 'error';

?>