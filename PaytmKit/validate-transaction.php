<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
session_start(); // session start for rechecking the response send by paytm

// following files need to be included
require_once("./lib/encdec_paytm.php");
/* initialize an array */
$paytmParams = array();

/* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
$paytmParams["MID"] = "Enter Your Merchant ID"; 

/* Enter your order id which needs to be check status for */
$paytmParams["ORDER_ID"] = $_SESSION['ORDER_ID']; 
$paytmParams["CUST_ID"] = $_SESSION['CUST_ID'];
$paytmParams["INDUSTRY_TYPE_ID"] = $_SESSION['INDUSTRY_TYPE_ID'];
$paytmParams["CHANNEL_ID"] = $_SESSION['CHANNEL_ID'];
$paytmParams["TXN_AMOUNT"] = $_SESSION['TXN_AMOUNT'];
$paytmParams["WEBSITE"] = $_SESSION['WEBSITE'];
$paytmParams["CALLBACK_URL"] = $_SESSION['CALLBACK_URL'];

/**
* Generate checksum by parameters we have
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/

$checksum = getChecksumFromArray($paytmParams, "Enter Your Merchant Key");

/* put generated checksum value here */
$paytmParams["CHECKSUMHASH"] = $checksum;

/* prepare JSON string for request */
$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/order/status";

/* for Production */
// $url = "https://securegw.paytm.in/order/status";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
$response = curl_exec($ch); // response in json


$json_resp = json_decode($response, true); //decode json into an array

print_r($json_resp); // araay of response
echo $json_resp['STATUS']; //
echo "</br>";

if($json_resp['STATUS']=="TXN_SUCCESS"){
//if validation of payment success, remaining operation after success goes here
}
else{
//if validation of payment failed, remaining operation goes here
}
?>
