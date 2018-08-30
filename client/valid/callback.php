<?php
$url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer kOlh1O6QDFYiGm4mQoW9uGKlGBuA')); //setting custom header


$curl_post_data = array(
  //Fill in the request parameters with valid values
  'ShortCode' => '906238',
  'ResponseType' => 'Completed',
  'ConfirmationURL' => 'https://www.denkiminsurance.com/client/valid/confirmation.php',
    'ValidationURL' => 'https://www.denkiminsurance.com/client/valid/validation.php'
);

$data_string = json_encode($curl_post_data);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
print_r($curl_response);

?>