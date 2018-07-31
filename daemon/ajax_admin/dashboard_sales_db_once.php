<?php
/**
 * Created by PhpStorm.
 * User: zaza
 * Date: 7/27/2018
 * Time: 5:08 AM
 */

require_once '../api/DBApi.php';


$crmList = $_GET['crm_list'];
$dateType = $_GET['date_type'];
$fromDate = $_GET['from_date'];
$toDate = $_GET['to_date'];

$dbApi = DBApi::getInstance();
if ($dbApi->getSubDomain() == '')
{
    echo json_encode(array('no_cookie'));
    return;
}

$arrayCrm = array();

foreach ($crmList as $crm) {
    $crmID = $crm[0];
    $crmGoal = $crm[7];
    $crmList = $dbApi->getActiveCrmById($crmID);

    if ($crmList != null)
    {
        $crm_result = $dbApi->getCrmResult($crmID, $dateType, $fromDate, $toDate);
        if ('error' == $crm_result)
            $arrayCrm[] = json_encode(array('error', $crmID));
        else if (0 == count($crm_result))
            $arrayCrm[] = json_encode(array('no_result', $crmID));
        else
            $arrayCrm[] = json_encode(array('success', $crmID, $crmGoal, $crm_result));
    }
}

echo json_encode($arrayCrm);

?>