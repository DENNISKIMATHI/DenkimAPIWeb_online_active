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
if(isset($_GET['message']) && !empty($_GET['message']) && isset($_GET['type']) && !empty($_GET['type']))
{
	$message=$_GET['message'];
        $type=$_GET['type'];
        $good_bad_id=$type==1? 'good_upload_message': 'bad_upload_message';
	$message='<span id="'.$good_bad_id.'">'.$message.'</span>';
}



if(isset($_GET['e']) && !empty($_GET['e']) && 
        isset($_GET['f']) && !empty($_GET['f']) &&
        isset($_GET['s']) && !empty($_GET['s']) &&
        isset($_GET['t']) && !empty($_GET['t']) &&
        isset($_GET['pi']) && !empty($_GET['pi']) &&
        isset($_GET['as']) && !empty($_GET['as']) &
        isset($_GET['cn']) && !empty($_GET['cn']) &&
        isset($_GET['p']) && !empty($_GET['p']) &&
        isset($_GET['ex']) && !empty($_GET['ex'])
        )
{
                $email_address=trim($_GET['e']);
                $full_names=trim($_GET['f']);
                $source=trim($_GET['s']);
                $type=trim($_GET['t']);
                $policy_id=trim($_GET['pi']);
                $active_status=trim($_GET['as']);
                
                 $company_name=trim($_GET['cn']);
                $phone_number=trim($_GET['p']);
                $expiry_duration_days=trim($_GET['ex']);
                
                $make_status=$active_status=='active'? 'false': 'true';//if active make false and vice verser
               
                
                        
                $return_url=$source.'?e='.$email_address.'&f='.$full_names.'&p='.$phone_number;
                
                $request_source='/senior_administrator/policy_activate_deactivate.php';
                //fetch
                $returned_json_decoded= fetch_policy_user_type_specific($policy_id,$_SESSION['session_key'],$_SESSION['cookie'],$request_source);

                $check_is=$returned_json_decoded["check"];//check

                $message_is=$returned_json_decoded["message"];//message

               $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['cookie'],'Origin:'.$request_source);
                
                if($check_is==true)//if check is true
                {

                  
                        switch ($type) 
                        {//start of $type
                                case 1://Motor insurance
                                       
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditMotorInsurance";
                                        
                                        
                                        $excess_protector_percentage=$message_is['selected_benefits']['excess_protector_percentage']==true? 'true' : 'false';
                                        $political_risk_terrorism_percentage=$message_is['selected_benefits']['political_risk_terrorism_percentage']==true? 'true' : 'false';
                                        $aa_membership=$message_is['selected_benefits']['aa_membership']==true? 'true' : 'false';
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&insured_item_value='.$message_is['insured_item_value'].
                                                '&excess_protector_percentage='.$excess_protector_percentage.
                                                '&political_risk_terrorism_percentage='.$political_risk_terrorism_percentage.
                                                '&aa_membership='.$aa_membership;

                                       

                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Motor insurance policy has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Motor insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New activation for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                          header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }
                                        
                                break;
                                
                                
                                case 2://In patient medical insurance
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditMedicalInsurance";
                                        
                                        
                                        $selected_father_insurance=make_medical_array_edit($message_is['selected_father_insurance'],false);//get proper returnable array
                                        $selected_mother_insurance=make_medical_array_edit($message_is['selected_mother_insurance'],false);//get proper returnable array
                                        $selected_children_insurance=make_medical_array_edit($message_is['selected_children_insurance'],true);//get proper returnable array
                                        
                                        
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_father_insurance='.json_encode($selected_father_insurance).
                                                '&selected_mother_insurance='.json_encode($selected_mother_insurance).
                                                '&selected_children_insurance='.json_encode($selected_children_insurance);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", In patient insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", In patient insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                          header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 3://Accident insurance

                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditAccidentInsurance";
                                        
                                        
                                       
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_options='.json_encode($message_is['selected_options']);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Accident insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Accident insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }
                                break;
                                
                                case 4://Contractors all risk insurance

                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditContractorsAllRiskInsurance";
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&contract_price='.$message_is['contract_price'];

                                       
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Contractors all risk insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Contractors all risk insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }
                                break;
                                
                                case 5://Performance Bond insurance
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditPerfomanceBondInsurance";
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&contract_price='.$message_is['contract_price'];

                                       
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Performance Bond insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Performance Bond insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 6://Fire burglary theft insurance
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditFireBurglaryTheftInsurance";
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&fire_price='.$message_is['fire_price'].
                                                '&burglary_price='.$message_is['burglary_price'].
                                                '&all_risk_price='.$message_is['all_risk_price'];

                                       
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Fire burglary insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Fire burglary insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 7://Home insurance
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditHomeInsurance";
                                        
                                         $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&building_value='.$message_is['building_value'].
                                                '&content_value='.$message_is['content_value'].
                                                '&electronics_value='.$message_is['electronics_value'];

                                       
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Home insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Home insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                        header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 8://Maternity insurance
                                         $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditMaternityInsurance";
                                        
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_options='.json_encode($message_is['selected_options']);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Maternity insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Maternity insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 9://Dental insurance 
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditDentalInsurance";
                                        
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_options='.json_encode($message_is['selected_options']);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Dental insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Dental insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }
                                    
                                break;
                                
                                case 10://Optical insurance 
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditOpticalInsurance";
                                        
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_options='.json_encode($message_is['selected_options']);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Optical insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Optical insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                         header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                case 11://Out patient medical insurance
                                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyEditOutMedicalInsurance";
                                        
                                        
                                        $selected_father_insurance=make_medical_array_edit($message_is['selected_father_insurance'],false);//get proper returnable array
                                        $selected_mother_insurance=make_medical_array_edit($message_is['selected_mother_insurance'],false);//get proper returnable array
                                        $selected_children_insurance=make_medical_array_edit($message_is['selected_children_insurance'],true);//get proper returnable array
                                        
                                        
                                        
                                        $myvars='_id='.$policy_id.'&session_key='.$_SESSION['session_key'].'&active_status='.$make_status.
                                                '&selected_father_insurance='.json_encode($selected_father_insurance).
                                                '&selected_mother_insurance='.json_encode($selected_mother_insurance).
                                                '&selected_children_insurance='.json_encode($selected_children_insurance);

                                       
                                        
                                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                        
                                        $returned_json_decoded= json_decode($returned_json,true);//decode
                                        
                                        
                                        $check_is=$returned_json_decoded["check"];//check

                                        $message_is=$returned_json_decoded["message"];//message
                                        
                                      
                                        
                                        if($check_is==true)
                                        {
                                              
                                            //login and send message
                                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');
                                            if($make_status=='false')
                                            { 
                                                $message_send="Hello ".$full_names.", Out patient insurance policy insured at ".$company_name." has been deactivated. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            else
                                            {
                                               $message_send="Hello ".$full_names.", Out patient insurance policy insured at ".$company_name." has been activated. Policy documents will be sent to your Denkim Insurance wallet account and email ASAP. The policy will expire after ".$expiry_duration_days." days. Click the following link to view your policy details. ".$message_is_is;
                                            }
                                            //send message to notify on claim
                                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_activate_deactivate.php');
                                            $header_email_is="New report for policy ". strtoupper($company_name);
                                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_activate_deactivate.php'); 

                                           header('location: '.$return_url.'&message='.$message_is.'&type=1');
                                        }
                                        else
                                        {
                                          header('location: '.$return_url.'&message='.$message_is.'&type=2');
                                        }

                                break;
                                
                                
                                
                                default:
                                break;
                        }//end of $type
                  
                }
                else//else failed
                {

                        if($message_is=='')
                        {
                            header('location: ../logout.php?message=Your session has expired, please log in again!&type=2');
                        }
                        else
                        {
                             header('location: '.$return_url.'&message='.$message_is.'&type=2');
                        }

                } 
                  
}



