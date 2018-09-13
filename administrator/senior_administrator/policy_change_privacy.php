<?php
require '../../le_functions/functions.php';
require '../../le_functions/sessions.php';

if(loggedin() && !empty($_SESSION['session_key']) && !empty($_SESSION['cookie']))//if logged in and user_id session is not empty
{
			
}
else
{
session_destroy();		
header('location: ../ ');	
}


//setting edit message
if(isset($_GET['s']) && !empty($_GET['s']) && 
    isset($_GET['st']) && !empty($_GET['st']) &&
    isset($_GET['pn']) && !empty($_GET['pn']) &&  
    isset($_GET['rp']) && !empty($_GET['rp']) 
        )
{
	$type=$_GET['s'];
        $status=$_GET['st'];
        $policy_number=$_GET['pn'];
        $return_page=$_GET['rp'];
        
        switch ($status) 
        {// switch ($status) 
                case 'Public'://make private
                        //fetch
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.MakePolicyPrivate";

                        $myvars='session_key='.$_SESSION['session_key'].'&type='.$type.'&policy_number='.$policy_number;

                        $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['cookie'],'Origin: /senior_administrator/policy_change_privacy.php');

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode
                        
                        $check_is=$returned_json_decoded["check"];//check

                        $message_is=$returned_json_decoded["message"];//message
                        
                        if($check_is==true)
                        {
                             header('location: '.$return_page.'?message='.$message_is.'&type=1');
                        }
                        else 
                        {
                             header('location: '.$return_page.'?message='.$message_is.'&type=2');
                        }

                break;
                
                case 'Private'://make public
                         //fetch
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.MakePolicyPublic";

                        $myvars='session_key='.$_SESSION['session_key'].'&type='.$type.'&policy_number='.$policy_number;

                        $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['cookie'],'Origin: /senior_administrator/policy_change_privacy.php');

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode
                        
                        $check_is=$returned_json_decoded["check"];//check

                        $message_is=$returned_json_decoded["message"];//message
                        
                        if($check_is==true)
                        {
                             header('location: '.$return_page.'?message='.$message_is.'&type=1');
                        }
                        else 
                        {
                             header('location: '.$return_page.'?message='.$message_is.'&type=2');
                        }

                break;
            
                default:
                break;
        }// switch ($status) 
        
}