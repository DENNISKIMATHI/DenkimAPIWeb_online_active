<?php
require '../request_acc_number/functions.php';

header("Content-Type:application/json");

$data = json_decode(file_get_contents('php://input'), true);

        $TransactionType = $data ['TransactionType'];
        $TransID= $data ['TransID'];
        $TransTime = $data ['TransTime'];
        $TransAmount= $data ['TransAmount'];
        $BusinessShortCode = $data ['BusinessShortCode'];
        $BillRefNumber= $data ['BillRefNumber'];
        $InvoiceNumber= $data ['InvoiceNumber'];
        $OrgAccountBalance = $data ['OrgAccountBalance'];
        $ThirdPartyTransID= $data ['ThirdPartyTransID'];
        $MSISDN = $data ['MSISDN'];
		$FirstName = $data ['FirstName'];
        $MiddleName= $data ['MiddleName'];
        $LastName = $data ['LastName'];
        
        $con = mysqli_connect("localhost", "denkimin", 'B834#@G#Tter#1', "denkimin_payments");
        if (!$con) 
        {
        die("Connection failed: " . mysqli_connect_error());
        }
$sql="INSERT INTO mpesa_payments(TransactionType,TransID,TransTime,TransAmount,BusinessShortCode,BillRefNumber,InvoiceNumber,MSISDN,FirstName,MiddleName,LastName,OrgAccountBalance,ThirdPartyTransID)  
VALUES ( '$TransactionType', '$TransID', '$TransTime', '$TransAmount', '$BusinessShortCode', '$BillRefNumber', '$InvoiceNumber', '$MSISDN', '$FirstName', '$MiddleName', '$LastName', '$OrgAccountBalance','$ThirdPartyTransID' )";

$result= mysqli_query($con, $sql);


 
if($result)
{//if($result)
    
                //check the account number exists
                $select=select_from_table_on_one_condition(temp_acc_info_table_name,'account_number',$BillRefNumber);
             
               if(count($select)>0)// exists
               {
                   switch ($select['type']) 
                   {// switch ($select) 
                        case 'policy'://
                                $myvars='mode_of_payment=MPESA&amount_paid='.str_replace(',', '', $TransAmount).'&particulars='.$BillRefNumber.'&time_date_of_payment='.storable_datetime_function(time()).'&transaction_code='.$TransID.'&msidn='.$MSISDN.'&email_address='.$select['email'].'&policy_id='.$select['policy_id'];
        
                                $header_array= array('Authorization:'.$select['authorization'],'Origin:Clients/valid/confirmation.php');

                                $returned_json=send_curl_post('http://35.184.46.252:6969/denkimAPILogic/MainPackages.CreateDirectPayment',$myvars,$header_array);//cap output

                                $resp["ResultCode"] = 0;
                                $resp["ResultDesc"] = 'Confirmation Received successfully';
                                echo json_encode($resp);

                        break;
                        
                        case 'wallet'://
                                $myvars='mode_of_payment=MPESA&amount_paid='.str_replace(',', '', $TransAmount).
                                '&particulars='.$BillRefNumber.
                                '&time_date_of_payment='.storable_datetime_function(time()).
                                '&transaction_code='.$TransID.
                                '&msidn='.$MSISDN.
                                '&email_address='.$select['email'].
                                '&use_date='.$select['use_date'];
        
                                $header_array= array('Authorization:'.$select['authorization'],'Origin:Clients/valid/confirmation.php');

                                $returned_json=send_curl_post('http://35.184.46.252:6969/denkimAPILogic/MainPackages.CreateWalletCreditPayment',$myvars,$header_array);//cap output

                                $resp["ResultCode"] = 0;
                                $resp["ResultDesc"] = 'Confirmation Received successfully';
                                echo json_encode($resp);

                        break;
                    
                        

                        default:
                        break;
                   }// switch ($select) 
                   
                   
               }
               else
               {
                    $myvars='mode_of_payment=MPESA&amount_paid='.str_replace(',', '', $TransAmount).'&particulars='.$BillRefNumber.'&time_date_of_payment='.storable_datetime_function(time()).'&transaction_code='.$TransID.'&msidn='.$MSISDN;
        
                    $header_array= array('Authorization:'.'944e15799e5955f1d9ba5bc236daffe3ccdff2ebe7467c2a70e82d84eb42c30631e105cb0da98f00ac5ca7c793c03ccb','Origin:Clients/valid/confirmation.php');

                    $returned_json=send_curl_post('http://35.184.46.252:6969/denkimAPILogic/MainPackages.CreateMobilePayment',$myvars,$header_array);//cap output

                         $resp["ResultCode"] = 0;
                        $resp["ResultDesc"] = 'Confirmation Received successfully';
                        echo json_encode($resp);
               }
    
    
}//if($result)
else
{
            $resp["ResultCode"] = 1;
            $resp["ResultDesc"] = 'Confirmation Failed';
            echo json_encode($resp);
            
}




 
mysqli_close($con); 


function send_curl_post($url,$myvars,$header_array)
{
    $ch = curl_init( $url );//initialize response
   //$array=array('url'=>$url,'myvars'=>$myvars,'header_array'=>$header_array);
   //$data_string=json_encode($array);
   
    
        //send to channel
        
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);//ignore sign in
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);//ignore sign in
        curl_setopt( $ch, CURLOPT_POST, 1);//as post
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);//set fields
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array); 
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );//true to url
        curl_setopt( $ch, CURLOPT_HEADER, 0 );//header null
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);//catch the response
    /*
       $ch = curl_init('http://35.184.46.252/DenkimAPIWeb/channel_traffic.php');           
      curl_setopt( $ch, CURLOPT_POST, 1);//as post                                                                   
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                      
      curl_setopt($ch, CURLOPT_HEADER, 0);                                                                                                                   
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//catch the response  
      */  
       //echo curl_exec($ch);
       
        return curl_exec($ch);
    
}

function storable_datetime_function($time)
{
            date_default_timezone_set("Africa/Nairobi");//make time kenyan
            $my_day= date('H:i:s Y-m-d',$time);
            
            return $my_day;
            
}



?>