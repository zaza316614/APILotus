<?php

require_once '../api/DBApi.php';
require_once '../api/StripeApi.php';


session_start();
if (isset($_SESSION['sub_domain']))
	$subDomain = $_SESSION['sub_domain'];
else
	$subDomain = '';
session_write_close();

if ($subDomain != '')
{
    $dbApi = DBApi::getInstance();
    $subscriptionID = $dbApi->getPaymentSubscriptionID($subDomain);

    if ($subscriptionID != '')
    {
		$stripeApi = StripeApi::getInstance();
		$stripeApi->cancelSubscription($subscriptionID);

		$ret = $dbApi->updatePaymentSubscriptionID($subDomain, '');

		echo 'success';
		return;
    }
} 
else 
{
    echo 'no_cookie';
    return;
}

echo 'error';

?>