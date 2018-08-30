<?php
 $url = "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer WtuOnA1elkJ7c4RU5VqfBJ5oerMP')); //setting custom header
    $MERCHANT_ID = "906238";
    $passkey = "55298b1353757015d70dd024ea6822feddffd72f1502a68e0cf98794e16e70d9";
  
    $TIMESTAMP = date('YmdHis');
    $PASSWORD = base64_encode( $MERCHANT_ID . $passkey . $TIMESTAMP );
$curl_post_data = array(
  
  'BusinessShortCode' => $MERCHANT_ID,
  'Password' => $PASSWORD,
  'Timestamp' => $TIMESTAMP,
  'TransactionType' => 'CustomerPayBillOnline',
  'Amount' => '1',
  'PartyA' => '254716214868',
  'PartyB' => $MERCHANT_ID,
  'PhoneNumber' => '254716214868',
  'CallBackURL' => 'https://www.denkiminsurance.com/client/stkform.php',
  'AccountReference' => 'Lipa Deni ',
  'TransactionDesc' => 'Pay Now'
);

$data_string = json_encode($curl_post_data);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

$curl_response = curl_exec($curl);
//print_r($curl_response);

echo $curl_response;
?>