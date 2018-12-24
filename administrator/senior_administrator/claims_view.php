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










if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f'])  && !empty($_GET['_id']) && isset($_GET['_id']) )
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $_id=trim($_GET['_id']);
       
        
        
        $full_link="claims_view.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id;//for form submission
        $edit_link="claims_edit.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id;//for form submission
        $return_link="claims.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names;
        
        
         //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaimsSpecific";

        $myvars='session_key='.$_SESSION['session_key'].'&_id='.$_id.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims_view.php');

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
                  
                  $table.='<br><a href="'.$edit_link.'" title="Edit '.$claim_number.'">Edit</a>';
                  
                        //fetch remarks
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchClaimRemarks";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims_view.php');

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
                                    
                                    //make add remark link
                                    $add_remark_link="claims_add_remark.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number;//for form submission
       
                                    //append remarks
                                    $table.='<h4>Other claim remarks</h4><br>'.$table_remarks.'<a href="'.$add_remark_link.'" title="Add remark to '.$claim_number.'">Add</a>';
                                    
                                    //fetch assigned assesors
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchAssessorLossAdjusterAssignedClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims_view.php');

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

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims_view.php');

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

                                                            $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims_view.php');

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
                <a class="navbar-brand" href="../senior_administrator/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Senior</div>
                    <div class="email">Administrator</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons">account_box</i>View Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="personal_information_edit.php" title="Edit your name, phone number and national ID"><i class="material-icons">edit</i>Edit Profile</a></li>
							 <li><a href="change_password.php" title="Get a new password by using your existing password"><i class="material-icons">edit</i>Change Password</a></li>
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
                       <a href="../senior_administrator/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                       <a href="junior_administrators.php?c=2&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete junior admins">
                            <i class="material-icons">person</i>
                            <span>Junior Administrators</span>
                        </a>
                    </li> 
                    <li>
                       <a href="claim_handlers.php?c=3&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete claim handlers">
                            <i class="material-icons">person</i>
                            <span>Claim handlers</span>
                        </a>
                    </li>
                    <li>
                       <a href="assessor_loss_adjsuter.php?c=4&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete Assessors/Loss adjusters">
                            <i class="material-icons">person</i>
                            <span>Assessors/Loss adjusters</span>
                        </a>
                    </li> 
                    <li>
                       <a href="repair_garage.php?c=5&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete repair garages">
                            <i class="material-icons">person</i>
                            <span>Repair garages</span>
                        </a>
                    </li> 
                    <li>
                       <a href="towing_rescue.php?c=6&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete towing and rescue">
                            <i class="material-icons">person</i>
                            <span>Towing and rescue</span>
                        </a> 
                    </li>
                    <li>
                        <a href="clients.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete clients, view their policies, select, delete and edit policies for them, create and delete claims, also make, veiw, edit and delete payments">
                            <i class="material-icons">contacts</i>
                            <span>Clients</span>
                        </a>
                    </li>
					<li>
					<a href="upload_html_logo.php" title="Upload logo and html for your policies to the file server">
                           <i class="material-icons">attachment</i>
                            <span>File Server</span>
                        </a>
                    </li> 
                    
                     
                    
                    <li>
                        <a href="tasks.php?l=10&s=0" title="Add and delete tasks">
                            <i class="material-icons">alarm</i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    
                    
                    <li>
                        <a href="clients_information.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add and share clients information">
                            <i class="material-icons">share</i>
                            <span>Clients information</span>
                        </a>
                    </li>
                 <li>
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
                  <li>
                       <a href="mobile_payments.php?l=10&s=0&sc=seen_status&so=asc&re=100" title="View made mobile payments and assign them to correct client policies">
                            <i class="material-icons">account_balance_wallet</i>
                            <span>Mobile Payments</span>
                         <?php echo get_mobile_payments_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/senior_administrator/*');?></a>
                    </li>
                               
                
                    <li>
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/senior_administrator/*');?></a>
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
                                    <h2><?php echo $full_names;?></h2>
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
		 <a href="../senior_administrator/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 	 
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