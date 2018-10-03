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










if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re']) && isset($_GET['ss']) && !empty($_GET['ss']) && !empty($_GET['_id']) && isset($_GET['_id']) )
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/claims_view.php');
        $email_address=$personal_details_array['email_address'];
        $full_names=$personal_details_array['full_names'];
        
        $seen_status=trim(strtolower($_GET['ss']));
        $_id=trim($_GET['_id']);
       
        
        
        $full_link="claims_view.php?l=".$limit."&s=".$skip."&re=".$rows_every."&ss=".$seen_status."&_id=".$_id;//for form submission
       $return_link="claims.php?l=".$limit."&s=".$skip."&re=".$rows_every;
        
        if($seen_status=='unseen')//if claim is unseen make seen
        {
              //fetch
                $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaimsSpecificMakeSeen";

                $myvars='session_key='.$_SESSION['session_key'].'&_id='.$_id.'&email_address='.$email_address;

                $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/claims_view.php');

                $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

               // $returned_json_decoded= json_decode($returned_json,true);//decode

                //$check_is=$returned_json_decoded["check"];//check

                 //$message_is=$returned_json_decoded["message"];//message
        }
        
        
         //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaimsSpecific";

        $myvars='session_key='.$_SESSION['session_key'].'&_id='.$_id.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/claims_view.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         $message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        {
            
            
                  $_id=$message_is['_id']['$oid'];
                  $policy_number=$message_is['policy_number'];
                  $type_of_claim=$message_is['type_of_claim'];
                  $claim_number=$message_is['claim_number'];
                  $date_reported=$message_is['date_reported'];
                  $company_name=$message_is['company_name'];
                  $date_of_loss=$message_is['date_of_loss'];
                  $location_of_loss=$message_is['location_of_loss'];
                  $police_date_reported=$message_is['police_date_reported'];
                  $status=$message_is['status'];
                  $remarks=$message_is['remarks'];
                  $seen_status=$message_is['seen_status'];
                  
                  $table='<table class="table table-bordered table-hover table-responsive">
                            <tr>
                                <th>Policy number</th>
                                <td>'.$policy_number.'</td>
                            </tr>
                            <tr>
                                <th>Type of claim</th>
                                <td>'.$type_of_claim.'</td>
                            </tr>
                            <tr>
                                <th>Claim number</th>
                                <td>'.$claim_number.'</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>'.$date_reported.'</td>
                            </tr>
                            <tr>
                                <th>Company name</th>
                                <td>'.$company_name.'</td>
                            </tr>
                            <tr>
                                <th>Date of loss</th>
                                <td>'.$date_of_loss.'</td>
                            </tr>
                            <tr>
                                <th>Location of loss</th>
                                <td>'.$location_of_loss.'</td>
                            </tr>
                            <tr>
                                <th>Police date reported</th>
                                <td>'.$police_date_reported.'</td>
                            </tr>
                            <tr>
                                <th>Remarks</th>
                                <td>'.$remarks.'</td>
                          </table>';
                  
                 
                  
                        //fetch remarks
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchClaimRemarks";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/console/claims_view.php');

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check

                        $message_is=$returned_json_decoded["message"];//message
                        
                        $count=0;//set count
                                    //draw
                                    if($check_is==true)//if check is true
                                    {//start of if($check_is==true)
                                                    foreach ($message_is as $value) 
                                                    {//start of foreach $message_is as $value
                                                          //$claim_id=$value['claim_id'];
                                                          $claim_remark=$value['claim_remark'];
                                                          $commentator_email=$value['commentator_email'];
                                                          $time_stamp=$value['time_stamp'];
                                                          $full_names_is=$value['full_names'];
                                                          $phone_number=$value['phone_number'];


                                                          $row_color=$count%2;
                                                          $row_color=$row_color==0?'odd':'even';

                                                          $table_remarks=$table_remarks.'<tr class="'.$row_color.'" id="row_data">
                                                                                        <td>'.($count+1).'</td>  
                                                                                                        <td id="claim_remark_td'.$count.'" >'.$claim_remark.'</td>
                                                                                                        <td id="full_names_td'.$count.'" >'.$full_names_is.'</td> 
                                                                                                        <td id="commentator_email_td'.$count.'" >'.$commentator_email.'</td>  
                                                                                                        <td id="phone_number_td'.$count.'" >'.$phone_number.'</td> 
                                                                                                        <td id="time_stamp_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                                        </tr>';
                                                          
                                                          $count++;
                                                          
                                                    }//end of foreach $message_is as $value
                                                    
                                                     $table_head='<tr bgcolor="white">
                                                                    <th>#</th>
                                                                    <th><a href="#"onmouseover="hover_link(\'claim_remark_td\',\''.$count.'\');" onmouseout="out_link(\'claim_remark_td\',\''.$count.'\');" >Claim remark</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'full_names_td\',\''.$count.'\');" onmouseout="out_link(\'full_names_td\',\''.$count.'\');" >Commentator full names</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'commentator_email_td\',\''.$count.'\');" onmouseout="out_link(\'commentator_email_td\',\''.$count.'\');" >Commentator email</a></th>
                                                                    <th><a href="#"onmouseover="hover_link(\'phone_number_td\',\''.$count.'\');" onmouseout="out_link(\'phone_number_td\',\''.$count.'\');" >Commentator phone number</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'time_stamp_td\',\''.$count.'\');" onmouseout="out_link(\'time_stamp_td\',\''.$count.'\');" >Date</a></th>
                                                                    </tr>';
                                                     
                                                     $table_remarks='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table_remarks.'</table>';
                                    }//end of if if($check_is==true)
                                    
                                    
                                    //fetch assigned assesors
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchAssessorLossAdjusterAssignedClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/console/claims_view.php');

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check

                        $message_is=$returned_json_decoded["message"];//message
                        
                        $count=0;//set count
                        
                                    //draw
                                    if($check_is==true)//if check is true
                                    {//start of if($check_is==true)
                                                    foreach ($message_is as $value) 
                                                    {//start of foreach $message_is as $value
                                                          //$claim_id=$value['claim_id'];
                                                          $claim_handler_email=$value['claim_handler_email'];
                                                          $assessor_email=$value['assessor_email'];
                                                          $time_stamp=$value['time_stamp'];
                                                          $claim_handler_full_names=$value['claim_handler_full_names'];
                                                          $claim_handler_phone_number=$value['claim_handler_phone_number'];
                                                          $assessor_full_names=$value['assessor_full_names'];
                                                          $assessor_phone_number=$value['assessor_phone_number'];


                                                          $row_color=$count%2;
                                                          $row_color=$row_color==0?'odd':'even';
                                                          
                                                          //view claims
                                                         $view_assessor_reports="claims_view_assessor_reports.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&c_h_e=".$claim_handler_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&c_h_f_n=".$claim_handler_full_names.'&c_h_p_n='.$claim_handler_phone_number;//for form submission
                                                                        
                                                          $table_assigned_assessors=$table_assigned_assessors.'<tr class="'.$row_color.'" id="row_data">
                                                                                        <td>'.($count+1).'</td>  
                                                                                                        <td id="claim_handler_full_names_td'.$count.'" >'.$claim_handler_full_names.'</td>
                                                                                                        <td id="claim_handler_email_td'.$count.'" >'.$claim_handler_email.'</td>
                                                                                                        <td id="claim_handler_phone_number_td'.$count.'" >'.$claim_handler_phone_number.'</td>
                                                                                                        <td id="assessor_email_td'.$count.'" >'.$assessor_email.'</td> 
                                                                                                        <td id="assessor_full_names_td'.$count.'" >'.$assessor_full_names.'</td> 
                                                                                                        <td id="assessor_phone_number_td'.$count.'" >'.$assessor_phone_number.'</td> 
                                                                                                        <td id="time_stamp_2_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                                                        <td id="view_add_report_td'.$count.'" ><a href="'.$view_assessor_reports.'" title="View report by claim number '.$assessor_full_names.'">View</td> 
                                                                                        </tr>';
                                                          
                                                          $count++;
                                                          
                                                    }//end of foreach $message_is as $value
                                                    
                                                     $table_head='<tr bgcolor="white">
                                                                    <th>#</th>
                                                                    <th><a href="#"onmouseover="hover_link(\'claim_handler_full_names_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_full_names_td\',\''.$count.'\');" >Claim handler full names</a></th>
                                                                    <th><a href="#"onmouseover="hover_link(\'claim_handler_email_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_email_td\',\''.$count.'\');" >Claim handler email</a></th>
                                                                    <th><a href="#"onmouseover="hover_link(\'claim_handler_phone_number_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_phone_number_td\',\''.$count.'\');" >Claim handler phone number</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'assessor_email_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_email_td\',\''.$count.'\');" >Assessor/Loss adjuster email</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'assessor_full_names_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_full_names_td\',\''.$count.'\');" >Assessor/Loss adjuster full names</a></th>
                                                                    <th><a href="#"onmouseover="hover_link(\'assessor_phone_number_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_phone_number_td\',\''.$count.'\');" >Assessor/Loss adjuster phone number</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'time_stamp_2_td\',\''.$count.'\');" onmouseout="out_link(\'time_stamp_2_td\',\''.$count.'\');" >Date</a></th>
                                                                    <th><a href="#" onmouseover="hover_link(\'view_add_report_td\',\''.$count.'\');" onmouseout="out_link(\'view_add_report_td\',\''.$count.'\');" >Report</a></th>
                                                                </tr>';
                                                     
                                                     $table_assigned_assessors='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table_assigned_assessors.'</table>';
                                    }//end of if if($check_is==true)
                                    
                                   
                                    //append add assessors
                                    $table.='<h4>Assigned Assessors/Loss adjusters</h4><br>'.$table_assigned_assessors;
                                    
                                     //fetch assigned garages
                                    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchRepairGarageAssignedClaims";

                                    $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/console/claims_view.php');

                                    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                                    $returned_json_decoded= json_decode($returned_json,true);//decode

                                    $check_is=$returned_json_decoded["check"];//check

                                    $message_is=$returned_json_decoded["message"];//message

                                    $count=0;//set count

                                                //draw
                                                if($check_is==true)//if check is true
                                                {//start of if($check_is==true)
                                                                foreach ($message_is as $value) 
                                                                {//start of foreach $message_is as $value
                                                                      //$claim_id=$value['claim_id'];
                                                                      $assessor_email=$value['assessor_email'];
                                                                      $repair_garage_email=$value['repair_garage_email'];
                                                                      $time_stamp=$value['time_stamp'];
                                                                      $assessor_full_names=$value['assessor_full_names'];
                                                                      $assessor_phone_number=$value['assessor_phone_number'];
                                                                      $repair_garage_full_names=$value['repair_garage_full_names'];
                                                                      $repair_garage_phone_number=$value['repair_garage_phone_number'];


                                                                      $row_color=$count%2;
                                                                      $row_color=$row_color==0?'odd':'even';

                                                                      //view claims
                                                                      $view_garage_reports="claims_view_garage_reports.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&r_g_e=".$repair_garage_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&r_g_f_n=".$repair_garage_full_names.'&r_g_p_n='.$repair_garage_phone_number;//for form submission



                                                                      $table_assigned_repair_garages=$table_assigned_repair_garages.'<tr class="'.$row_color.'" id="row_data">
                                                                                                    <td>'.($count+1).'</td>  
                                                                                                                    <td id="assessor_full_names_2_td'.$count.'" >'.$assessor_full_names.'</td> 
                                                                                                                    <td id="assessor_email_2_td'.$count.'" >'.$assessor_email.'</td> 
                                                                                                                    <td id="assessor_phone_number_2_td'.$count.'" >'.$assessor_phone_number.'</td>
                                                                                                                    <td id="repair_garage_email_td'.$count.'" >'.$repair_garage_email.'</td>
                                                                                                                    <td id="repair_garage_full_names_td'.$count.'" >'.$repair_garage_full_names.'</td>
                                                                                                                    <td id="repair_garage_phone_number_td'.$count.'" >'.$repair_garage_phone_number.'</td>
                                                                                                                    <td id="time_stamp_3_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                                                                    <td id="view_add_cost_sheet_td'.$count.'" ><a href="'.$view_garage_reports.'" title="View cost sheets by '.$repair_garage_full_names.'">View</td> 
                                                                                                    </tr>';

                                                                      $count++;

                                                                }//end of foreach $message_is as $value

                                                                 $table_head='<tr bgcolor="white">
                                                                                <th>#</th>
                                                                                <th><a href="#" onmouseover="hover_link(\'assessor_full_names_2_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_full_names_2_td\',\''.$count.'\');" >Assessor/Loss adjuster full names</a></th>
                                                                                <th><a href="#" onmouseover="hover_link(\'assessor_email_2_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_email_2_td\',\''.$count.'\');" >Assessor/Loss adjuster email</a></th>
                                                                                <th><a href="#"onmouseover="hover_link(\'assessor_phone_number_2_td\',\''.$count.'\');" onmouseout="out_link(\'assessor_phone_number_2_td\',\''.$count.'\');" >Assessor/Loss adjuster phone number</a></th>

                                                                                <th><a href="#"onmouseover="hover_link(\'repair_garage_email_td\',\''.$count.'\');" onmouseout="out_link(\'repair_garage_email_td\',\''.$count.'\');" >Repair garage email</a></th>
                                                                                <th><a href="#"onmouseover="hover_link(\'repair_garage_full_names_td\',\''.$count.'\');" onmouseout="out_link(\'repair_garage_full_names_td\',\''.$count.'\');" >Repair garage full names</a></th>
                                                                                <th><a href="#"onmouseover="hover_link(\'repair_garage_phone_number_td\',\''.$count.'\');" onmouseout="out_link(\'repair_garage_phone_number_td\',\''.$count.'\');" >Repair garage phone number</a></th>
                                                                                <th><a href="#" onmouseover="hover_link(\'time_stamp_3_td\',\''.$count.'\');" onmouseout="out_link(\'time_stamp_3_td\',\''.$count.'\');" >Date</a></th>
                                                                                <th><a href="#" onmouseover="hover_link(\'view_add_cost_sheet_td\',\''.$count.'\');" onmouseout="out_link(\'view_add_cost_sheet_td\',\''.$count.'\');" >Cost sheet</a></th>

                                                                                </tr>';

                                                                 $table_assigned_repair_garages='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table_assigned_repair_garages.'</table>';
                                                }//end of if if($check_is==true)


                                                //append add repair garage
                                                $table.='<h4>Assigned repair garages</h4><br>'.$table_assigned_repair_garages;
                                                
                                                
                                                    
                                                            //fetch table for approvals
                                                     
                                                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchClaimApprovals";

                                                            $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                                                            $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/console/claims_view.php');

                                                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                                                            $returned_json_decoded= json_decode($returned_json,true);//decode

                                                            $check_is=$returned_json_decoded["check"];//check

                                                            $message_is=$returned_json_decoded["message"];//message

                                                            $count=0;//set count
                                                            
                                                                //draw
                                                                if($check_is==true)//if check is true
                                                                {//start of if($check_is==true)
                                                                                foreach ($message_is as $value) 
                                                                                {//start of foreach $message_is as $value
                                                                                      $_approval_id=$value['_id']['$oid'];
                                                                                      //$claim_id=$value['claim_id'];
                                                                                      $claim_handler_email_is_is=$value['claim_handler_email'];
                                                                                      $approval_status_is_is=$value['approval_status'];
                                                                                      $time_stamp_is_is=$value['time_stamp'];
                                                                                      $claim_handler_full_names_is_is=$value['claim_handler_full_names'];
                                                                                      $claim_handler_phone_number_is_is=$value['claim_handler_phone_number'];
                                                                                     


                                                                                      $row_color=$count%2;
                                                                                      $row_color=$row_color==0?'odd':'even';

                                                                                      //view claims
                                                                                      $view_garage_reports="claims_view_garage_reports.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&r_g_e=".$repair_garage_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&r_g_f_n=".$repair_garage_full_names.'&r_g_p_n='.$repair_garage_phone_number;//for form submission



                                                                                      $table_approvals=$table_approvals.'<tr class="'.$row_color.'" id="row_data">
                                                                                                                    <td>'.($count+1).'</td>  
                                                                                                                                    <td id="claim_handler_full_names_4_td'.$count.'" >'.$claim_handler_full_names_is_is.'</td> 
                                                                                                                                    <td id="claim_handler_email_4_td'.$count.'" >'.$claim_handler_email.'</td> 
                                                                                                                                    <td id="claim_handler_phone_number_4_td'.$count.'" >'.$claim_handler_phone_number_is_is.'</td>
                                                                                                                                    <td id="time_stamp_4_td'.$count.'" >'.return_date_function($time_stamp_is_is).'</td> 
                                                                                                                                    
                                                                                                                        </tr>';

                                                                                      $count++;

                                                                                }//end of foreach $message_is as $value

                                                                                 $table_head='<tr bgcolor="white">
                                                                                                    <th>#</th>
                                                                                                    <th><a href="#" onmouseover="hover_link(\'claim_handler_full_names_4_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_full_names_4_td\',\''.$count.'\');" >AApproving officer full names</a></th>
                                                                                                    <th><a href="#" onmouseover="hover_link(\'claim_handler_email_4_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_email_4_td\',\''.$count.'\');" >Approving officer email</a></th>
                                                                                                    <th><a href="#"onmouseover="hover_link(\'claim_handler_phone_number_4_td\',\''.$count.'\');" onmouseout="out_link(\'claim_handler_phone_number_4_td\',\''.$count.'\');" >Approving officer phone number</a></th>
                                                                                                    <th><a href="#"onmouseover="hover_link(\'time_stamp_4_td\',\''.$count.'\');" onmouseout="out_link(\'time_stamp_4_td\',\''.$count.'\');" >Approval date</a></th>

                                                                                                </tr>';

                                                                                 $table_approvals='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table_approvals.'</table>';
                                                                }//end of if if($check_is==true)


                                                                    //append add repair garage
                                                                    $table_approvals='<h4>Approvals</h4><br>'.$table_approvals;
                                                
        }
        else//else failed
        {
           
            if($message_is=='')
            {
                header('location: ../logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
                $message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
           // header('location: logs_view.php?c='.$_GET['c'].'&l='.$_GET['l'].'&s='.$_GET['s'].'&message='.$message_is.'&type=2');//
        }
}

    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <title>View claim specific</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="../../css/main.css" />
	
    <!-- Bootstrap Core Css -->
    <link href="../../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../../css/style.css" rel="stylesheet">

   
    <link href="../../css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-orange">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
  
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
               
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../console/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
             </div>
            
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="../../images/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                       
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons">account_box</i>View Profile</a></li>
                           <li role="seperator" class="divider"></li>
                            <li><a href="../logout.php" Title="Click here to sign out" id="logout_link"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active">
                        <a href="../../client/console/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                
					 <li>
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
                    
                     <li>
                        <a href="wallet_first.php" title="Add money to wallet">
                            <i class="material-icons">money</i>
                            <span>Wallet</span>
                        </a>
                    </li>
					<a href="claims.php?l=10&s=0&re=10" title="View claims">
                           <i class="material-icons">attachment</i>
                            <span>Claims</span>
							<?php echo get_claims_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?>
                        </a>
                    </li> 
                   <li>
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?></a>
                    </li>
                    <li>
                        <?php  
                        //fetch email
                        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/index.php');
                        $email_address=$personal_details_array['email_address'];
        
                        $total=0;
                        $payment=0;
                        $balance=0;

                        //get payment combined
                //fetch

                                    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyFetchCombinedAll";

                                    $myvars='session_key='.$_SESSION['session_key'].'&email='.$email_address;

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/index.php');

                                    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                                    $returned_json_decoded= json_decode($returned_json,true);//decode

                                    $check_is_2=$returned_json_decoded["check"];//check

                                   // echo $check_is_2.'=='.$policy_id.'<br>';
                                    if($check_is_2==true)//if check is true
                                    {//if($check_is_2==true)//if check is true
                                         $message_is_now=$returned_json_decoded["message"];//message
                                         $totals_info=get_aggregate_totals_payments_client_full_json($message_is_now);

                                         $total=$totals_info['total'];
                                        $payment=$totals_info['payment'];
                                        $balance=$totals_info['balance']==0?$totals_info['total']:$totals_info['balance'];


                                    }//if($check_is_2==true)//if check is true
                                    

                    /*
                                for ($index = 1; $index < 17; $index++) 
                                {
                                        $totals_info=get_aggregate_totals_payments_client($index,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/index.php',$email_address);

                                        $total+=$totals_info['total'];
                                        $payment+=$totals_info['payment'];
                                        $balance+=$totals_info['balance']==0?$totals_info['total']:$totals_info['balance'];
                                            //echo json_encode($totals_info).'<br>';
                                }
                     * 
                     */
                             ?>
                        <table style="font-size: 9px">
                <tr>
                    <th>Total premium charged</th><td style="text-align: right;">KES. <?php echo number_format($total);?></td>
                </tr>
                <tr>
                <th>Total premium paid</th><td style="text-align: right;">KES. <?php echo number_format($payment);?></td>
                </tr>
                <?php
                $credit=0;
                $show_balance=$balance;
                if($balance<0)
                {
                    $credit=$balance;
                    $show_balance=0;
                }
                
                        ?>
                         <tr>
                        <th>Total outstanding balance</th><td style="text-align: right;">KES. <?php echo number_format($show_balance);?></td>
                        </tr>

                         <tr>
                        <th>Credit on account</th><td style="text-align: right;">KES. <?php echo number_format($credit);?></td>
                        </tr>
                        <?php
                
                ?>
               
                
            
        </table>
                        </li>
                  <!--   <li class="header">LABELS</li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-red">donut_large</i>
                            <span>Important</span>
                        </a>
                    </li> -->
                 
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal"></div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
      
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
           <!-- innerbody -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
                            <?php echo $message;?><br>
		<?php echo $table;?>
          <?php echo $table_approvals;?>
        <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../../javascript/highlight.js"></script>
		 
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
		  <a href="../../client/console/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br>
			  
            </div>
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="../../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../../plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="../../js/admin.js"></script>

    <!-- Demo Js -->
    <script src="../../js/demo.js"></script>
</body>

</html>