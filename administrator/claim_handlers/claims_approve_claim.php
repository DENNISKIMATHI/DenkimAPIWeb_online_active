<?php
require '../../le_functions/sessions.php';
require '../../le_functions/functions.php';


if(loggedin() && !empty($_SESSION['session_key']) && !empty($_SESSION['cookie']))//if logged in and user_id session is not empty
{
			
}
else
{
session_destroy();		
header('location: ../ ');	
}

//setting edit message
if(isset($_GET['message']) && !empty($_GET['message']) && isset($_GET['type']) && !empty($_GET['type']))
{
	$message=$_GET['message'];
        $type=$_GET['type'];
        $good_bad_id=$type==1? 'good_upload_message': 'bad_upload_message';
	$message='<span id="'.$good_bad_id.'">'.$message.'</span>';
}


if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re'])  && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f'])  && !empty($_GET['_id']) && isset($_GET['_id']) && isset($_GET['c_n']) && !empty($_GET['c_n']) )
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $_id=trim($_GET['_id']);
        $claim_number=trim($_GET['c_n']);
       
        
        
        $full_link="claims_approve_claim.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id."&c_n=".$claim_number;//for form submission
        $return_link="claims_view.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id;//for form submission
        
       
            
                 
                  
                 
                       
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.ApproveClaim";

                            $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                             $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/claim_handlers/claims_approve_claim.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                     //login and send message
                                    $message_is_is=login_behalf_of_client($email_address,'/claim_handlers/claims_approve_claim.php');
                                    //get client phone number
                                    $client_info=get_specific_client_details($_SESSION['session_key'],$_SESSION['cookie'],$email_address,'/claim_handlers/claims_approve_claim.php');
                                    $message_send="Hello ".$full_names.", claim number ".$claim_number." has been approved in your DENKIM account. Please login with the following link to view. ".$message_is_is;
                                    //send message to notify on claim
                                    send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$client_info['phone_number'],'/claim_handlers/claims_approve_claim.php');
                                     //send email
                                    $header_email_is="Claim number ". strtoupper($claim_number)." approval";
                                    send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$client_info['email_address'],$header_email_is,$message_send,'/claim_handlers/claims_approve_claim.php'); 
                                  
                
                                  header('location: '.$return_link.'&message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: '.$return_link.'&message='.$message_is.'&type=2');//
                            } 
                       

                  
       
}








