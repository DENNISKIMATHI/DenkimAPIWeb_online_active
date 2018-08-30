<?php
if(
isset($_POST['total']) && !empty($_POST['total']) &&
        isset($_POST['phone_number']) && !empty($_POST['phone_number']) &&
        isset($_POST['account_number']) && !empty($_POST['account_number']) 
        
        )
{
        $total=$_POST['total']; 
        $phone_number=$_POST['phone_number']; 
        $account_number=$_POST['account_number'];
       
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            $credentials = base64_encode('dKKqrzyI2P9GGuYiq37W3ubNbaaPvYxW:yVSrgK1JBvZRaGvz');
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                                                                      
            curl_setopt($curl, CURLOPT_HEADER, 0);                                                                                                                   
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//catch the response  
            
             $curl_response = curl_exec($curl);
             
            $array= json_decode($curl_response,true);
           
            $token=$array['access_token'];
            
            
             $url = "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header
                    $MERCHANT_ID = "906238";
                    $passkey = "55298b1353757015d70dd024ea6822feddffd72f1502a68e0cf98794e16e70d9";

                    $TIMESTAMP = date('YmdHis');
                    $PASSWORD = base64_encode( $MERCHANT_ID . $passkey . $TIMESTAMP );
                $curl_post_data = array(

                  'BusinessShortCode' => $MERCHANT_ID,
                  'Password' => $PASSWORD,
                  'Timestamp' => $TIMESTAMP,
                  'TransactionType' => 'CustomerPayBillOnline',
                  'Amount' => $total,
                  'PartyA' => $phone_number,
                  'PartyB' => $MERCHANT_ID,
                  'PhoneNumber' => $phone_number,
                  'CallBackURL' => 'https://www.denkiminsurance.com/client/stkform.php',
                  'AccountReference' => $account_number,
                  'TransactionDesc' => 'Denkim pay automatically'
                );

                $data_string = json_encode($curl_post_data);

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                                                                      
            curl_setopt($curl, CURLOPT_HEADER, 0);                                                                                                                   
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//catch the response  

                $curl_response = curl_exec($curl);
                //print_r($curl_response);

                echo $curl_response;
         
            //echo $token['access_token'];
            
                
}            