<?php
/**
 * Created by PhpStorm.
 * User: zaza
 * Date: 7/27/2018
 * Time: 5:08 AM
 */

require_once '../api/DBApi.php';


$crmID = $_GET['crm_id'];
$crmGoal = $_GET['crm_goal'];
$dateType = $_GET['date_type'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

$dbApi = DBApi::getInstance();
if ($dbApi->getSubDomain() == '')
{
    echo json_encode(array('no_cookie'));
    return;
}

$crmList = $dbApi->getActiveCrmById($crmID);

if ($crmList != null)
{
    $crm_result = $dbApi->getCrmResult($crmID, $dateType, $fromDate, $toDate);
    if ('error' == $crm_result)
        echo json_encode(array('error', $crmID));
    else if (0 == count($crm_result))
        echo json_encode(array('no_result', $crmID));
    else
        echo json_encode(array('success', $crmID, $crmGoal, $crm_result));

    return;
}

echo json_encode(array('error', $crmID));

?>