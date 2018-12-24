﻿<?php
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










if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f']))
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        
        $full_link="claims.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names;//for form submission
        $link_without_sort_column_sort_order="claims.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names;//for headers sorting
        $link_without_limit_skip_rows_every="claims.php?e=".$email_address."&f=".$full_names;//for browsing
        $delete_link="claims_delete.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names;//for form submission
        $view_link="claims_view.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names;//for form submission
        
        //form submission
        if(isset($_POST['headers_is']) && !empty($_POST['headers_is']) &&
                 is_numeric($_POST['headers_is']) && 
                isset($_POST['limit_is']) && !empty($_POST['limit_is']) && 
                is_numeric($_POST['limit_is']) && 
                ( $_POST['skip_is']==0 || is_numeric($_POST['skip_is']) ))
        {
            $new_limit=trim($_POST['limit_is']);
            $new_skip=trim($_POST['skip_is']);
            $new_rows_every=trim($_POST['headers_is']);
            
             header('location: '.$link_without_limit_skip_rows_every.'&l='.$new_limit.'&s='.$new_skip.'&re='.$new_rows_every.' ');//redirect back to form correctly
        }
        
        //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaims";

        $myvars='session_key='.$_SESSION['session_key'].'&limit='.$limit.'&skip='.$skip.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
           $count=$skip;//make count skipped rows
            
            $total_for_table_rows=$skip+$limit;//total for table highlight js function
            $table_head='<tr bgcolor="white">
                         <th>#</th>
                             <th><a href="#"onmouseover="hover_link(\'type_of_claim_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'type_of_claim_td\',\''.$total_for_table_rows.'\');" >Type of claim</a></th>
                            <th><a href="#"onmouseover="hover_link(\'claim_number_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'claim_number_td\',\''.$total_for_table_rows.'\');" >Claim number</a></th>
                            <th><a href="#" onmouseover="hover_link(\'company_name_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'company_name_td\',\''.$total_for_table_rows.'\');" >Company name</a></th>
                            <th><a href="#" onmouseover="hover_link(\'status_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'status_td\',\''.$total_for_table_rows.'\');" >Status</a></th>
                             <th><a href="#"onmouseover="hover_link(\'date_reported_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'date_reported_td\',\''.$total_for_table_rows.'\');" >Date reported</a></th>
                           
                            <th><a href="#" onmouseover="hover_link(\'view_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'view_td\',\''.$total_for_table_rows.'\');" >View</a></th>
                            <th><a href="#" onmouseover="hover_link(\'delete_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'delete_td\',\''.$total_for_table_rows.'\');" >Delete</a></th>
                            </tr>';
            $from_one_counter=1;//used to know how many rows are printed from one so as to append table head
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $_id=$value['_id']['$oid'];
                  $type_of_claim=$value['type_of_claim'];
                  $claim_number=$value['claim_number'];
                  $date_reported=$value['date_reported'];
                  $company_name=$value['company_name'];
                  $status=$value['status'];
                  $seen_status=$value['seen_status'];
                  
                  
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                                                         
                  $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                <td>'.($count+1).'</td>  
                                                                <td id="type_of_claim_td'.$count.'" >'.$type_of_claim.'</td>
                                                                <td id="claim_number_td'.$count.'" >'.$claim_number.'</td>
                                                                <td id="company_name_td'.$count.'" >'.$company_name.'</td>  
                                                                <td id="status_td'.$count.'" >'.$status.'</td> 
                                                                    <td id="date_reported_td'.$count.'" >'.$date_reported.'</td> 
                                                                <td id="view_td'.$count.'" ><a href="'.$view_link.'&_id='.$_id.'" title="View and edit '.$claim_number.'">View/Edit</a></td>   
                                                                <td id="delete_td'.$count.'" ><span id="red_text_span"><a href="'.$delete_link.'&_id='.$_id.'&cn='.$claim_number.'" title="Remove '.$claim_number.'">Delete</a></span></td>   
                                                  
                                                </tr>';
                  $table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header
                  
                  $count++;
                  $from_one_counter++;
            }//end of foreach $message_is as $value
            
            $table='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table.'
                         </table>';
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
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
        
        
                    //submit
                    if(isset($_POST['policy_number']) && !empty($_POST['policy_number']) && 
                    isset($_POST['type_of_claim']) && !empty($_POST['type_of_claim']) &&
                    isset($_POST['claim_number']) && !empty($_POST['claim_number']) &&
                    isset($_POST['date_reported']) && !empty($_POST['date_reported']) &&
                    isset($_POST['company_name']) && !empty($_POST['company_name']) &&
                    isset($_POST['date_of_loss']) && !empty($_POST['date_of_loss']) &&
                    isset($_POST['location_of_loss']) && !empty($_POST['location_of_loss']) &&
                    isset($_POST['police_date_reported']) && !empty($_POST['police_date_reported']) &&
                    isset($_POST['status']) && !empty($_POST['status']) &&
                    isset($_POST['remarks']) && !empty($_POST['remarks']) 
                    )
                    {  
                        $policy_number=trim($_POST['policy_number']);
                        $type_of_claim=trim($_POST['type_of_claim']);
                        $claim_number=trim($_POST['claim_number']);
                        $date_reported=trim($_POST['date_reported']);
                        $company_name=trim($_POST['company_name']);
                        $date_of_loss=trim($_POST['date_of_loss']);
                        $location_of_loss=trim($_POST['location_of_loss']);
                        $police_date_reported=trim($_POST['police_date_reported']);
                        $status=trim($_POST['status']);
                        $remarks=trim($_POST['remarks']);

                       
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorCreateClaim";

                            $myvars='session_key='.$_SESSION['session_key'].'&email_address='.$email_address.'&policy_number='.$policy_number.'&type_of_claim='.$type_of_claim.'&claim_number='.$claim_number.'&date_reported='.$date_reported.'&company_name='.$company_name.'&date_of_loss='.$date_of_loss.'&location_of_loss='.$location_of_loss.'&police_date_reported='.$police_date_reported.'&status='.$status.'&remarks='.$remarks;

                             $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/claims.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                    
                                    //login and send message
                                    $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/claims.php');
                                    //get client phone number
                                    $client_info=get_specific_client_details($_SESSION['session_key'],$_SESSION['cookie'],$email_address,'/senior_administrator/claims.php');
                                    //echo json_encode($client_info);
                                    $message_send="Hello ".$full_names.", claim number ".$claim_number." of type ".$type_of_claim." has been created in your DENKIM account. Please login with the following link to view. ".$message_is_is;
                                    //send message to notify on claim
                                    send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$client_info['phone_number'],'/senior_administrator/claims.php');
                                    //send email
                                    $header_email_is="New claim number ". strtoupper($claim_number);
                                    send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$client_info['email_address'],$header_email_is,$message_send,'/senior_administrator/claims.php'); 
                                  
                                    header('location: '.$full_link.'&message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: '.$full_link.'&message='.$message_is.'&type=2');//
                            } 
                       

                    }
     
                    
}

    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>Claims</title>
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
					<<li>
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
          <form action="<?php echo $full_link;?>" method="POST">
            <input type="text" name="policy_number" placeholder="Policy number"/>
            <input type="text" name="type_of_claim" placeholder="Type of claim"/>
            <input type="text" name="claim_number"  placeholder="Claim number"/>
            <input type="date" name="date_reported" title="Date reported" />
            <input type="text" name="company_name"  placeholder="Company name"/>
            <input type="date" name="date_of_loss" title="Date of loss" />
            <input type="text" name="location_of_loss"  placeholder="Location of loss"/>
            <input type="date" name="police_date_reported" title="Police date reported" />
            <input type="text" name="status" placeholder="Status" />
            <textarea name="remarks" cols="20" rows="10" placeholder="Remarks..."></textarea>
             <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
         </form>
         
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
			  <!-- innerbody -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2> </h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
						
		 
                            <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form><br>
                           <?php echo $table;?><br>
                             <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form>

                           <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
						   
                       
                    </div>
                </div>
            </div>
			 <a href="../senior_administrator/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 
            <!-- #END# -->
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