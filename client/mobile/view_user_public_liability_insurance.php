﻿<?php
require '../../le_functions/functions.php';
require '../../le_functions/sessions.php';

if(loggedin() && !empty($_SESSION['session_key']) && !empty($_SESSION['cookie']))//if logged in and user_id session is not empty
{
			
}
else
{
session_destroy();		
header('location: ../mobile_login.php?message=Your%20login%20link%20has%20expired!&type=2 ');	
}


//setting edit message
if(isset($_GET['message']) && !empty($_GET['message']) && isset($_GET['type']) && !empty($_GET['type']))
{
	$message=$_GET['message'];
        $type=$_GET['type'];
        $good_bad_id=$type==1? 'good_upload_message': 'bad_upload_message';
	 $message='<span id="'.$good_bad_id.'">'.$message.'</span>';
}




                //fetch email
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_public_liability_insurance.php');
        $email_address=$personal_details_array['email_address'];
        
                $type=12;
                //fetch
            $returned_json_decoded= fetch_policy_user_type($type,$email_address,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_public_liability_insurance.php');

            $check_is=$returned_json_decoded["check"];//check

            $message_is=$returned_json_decoded["message"];//message

            $list='';
            
            if($check_is==true)//if check is true
            {

                foreach ($message_is as $value) 
                {
                   //echo json_encode($value).'<hr>';
                    
                    $policy_number=$value['policy_number'];
                    
                    $policy_info= fetch_policy_type_specific($type,$policy_number,'/client/console/view_user_public_liability_insurance.php');
                    $policy_infocheck_is=$policy_info["check"];//check
                    $policy_infomessage_is=$policy_info["message"];//message
                    
                    $array_to_print=array();
                    //handle if policy check s true
                    if($policy_infocheck_is==true)
                    {
                         //echo json_encode($policy_infomessage_is).'<hr>';
                            $policy_id=$value['_id']['$oid'];
                           
                            $array_to_print['policy_id']=$policy_id;
                            $array_to_print['policy_number']=$policy_number;
                            $array_to_print['limit']=$value['limit'];
                            $array_to_print['active_status']=$value['active_status'];
                            $array_to_print['selected_policy_time_stamp']=$value['time_stamp'];
                            
                            $array_to_print['company_name']=$policy_infomessage_is['company_name'];
                            $array_to_print['premium_percentage']=$policy_infomessage_is['premium_percentage'];
                            $array_to_print['minimum']=$policy_infomessage_is['minimum'];
                            $array_to_print['expiry_duration_days']=$policy_infomessage_is['expiry_duration_days'];
                            $array_to_print['logo_url']=$policy_infomessage_is['logo_url'];
                            $array_to_print['html_url']=$policy_infomessage_is['html_url'];
                            $array_to_print['company_time_stamp']=$policy_infomessage_is['time_stamp'];
                            
                           
                            
                            
                            $returned_array=make_public_liability_policy_view($array_to_print);
                           
                            $payments_link='policy_view_payments_specific.php?s=view_user_public_liability_insurance.php&t='.$type.'&pi='.$returned_array['policy_id'].'&pn='.$returned_array['policy_number'].'&cn='.$returned_array['company_name'].'&pd='.$returned_array['policy_date'].'&edd='.$returned_array['expiry_duration_days'].'&pt='.$returned_array['total'];
                            $make_payments_link='policy_make_payments_specific.php?s=view_user_public_liability_insurance.php&t='.$type.'&pi='.$returned_array['policy_id'].'&pn='.$returned_array['policy_number'].'&cn='.$returned_array['company_name'].'&pd='.$returned_array['policy_date'].'&edd='.$returned_array['expiry_duration_days'].'&pt='.$returned_array['total'];
                            
                            
                            $full_payments_link='<a href="'.$payments_link.'" title="View '.$returned_array['policy_number'].' payments" class="btn btn-block btn-lg btn-warning waves-effect">View payments</a>';
                            $full_make_payments_link='<a style="background-color:green" href="'.$make_payments_link.'" title="Make '.$returned_array['policy_number'].' payments" class="btn btn-block btn-lg btn-success waves-effect">Make payment</a>';
                            
                            $list.=$returned_array['html'].'<br>'.$full_payments_link.'<br><br>'.$full_make_payments_link;
                    }
                    
                   
                    
                }
                         


            }
            else//else failed
            {

                    if($message_is=='')
                    {
                        header('location: ../mobile_logout.php?message=Your session has expired, please log in again!&type=2');
                    }
                    else
                    {
                         $message='<span id="bad_upload_message">'.$message_is.'</span>';
                    }

            } 
        



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
       <title>View Public liability insuranc</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />

    <!-- Wait Me Css -->
    <link href="plugins/waitme/waitMe.css" rel="stylesheet" />

    <!-- Bootstrap Select Css -->
    <link href="plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="css/style.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
</head>

<body class="theme-deep-orange">
    <!-- Page Loader -->
   
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
  
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
               
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="../../client/mobile/"> &nbsp DENKIM INSURANCE</a>
            </div>
           
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
         <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="../../images/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-weight:700;" >User</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">settings</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons col-deep-orange">person</i>View Profile</a></li>
                           <li role="seperator" class="divider"></li>
                            <li><a href="../logout.php" Title="Click here to sign out" id="logout_link"><i class="material-icons col-deep-orange">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
		 
            <!-- Menu -->
             <div class="menu">
                <ul class="list">
				
                    <li >
                        <a href="../../client/mobile/" title="Go to the main page">
						<i class="material-icons ">home</i>
                            
                            <span>Home</span>
                        </a>
                    </li>
                
					 <li>
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons ">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
                    
                     <li>
                        <a href="wallet_first.php" title="Add money to wallet">
                            <i class="material-icons ">payment</i>
                            <span>Wallet</span>
                        </a>
                    </li>
					<a href="claims.php?l=10&s=0&re=10" title="View claims">
                           <i class="material-icons ">attachment</i>
                            <span>Claims</span>
							<?php echo get_claims_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?>
                        </a>
                    </li> 
                   <li>
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons ">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?></a>
                    </li>
                   
				   <li>
                       <a href="how.php" title="Send and get messages">
                            <i class="material-icons ">work</i>
                            <span>How it Works</span>
                        </a>
                    </li>
                   
                                     
                  <li>
				      <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons col-deep-orange">widgets</i>
                            <span>Account Summary</span>
                        </a>
                        <ul class="ml-menu">
                                <li>
                        <script src="../../javascript/jquery-1.11.1.min.js"></script>
                        <script src="../../javascript/combined_totals.js"></script>
                        <script type="text/javascript">
                            send_combined_and_get_aggregate_totals();
                            
                            </script>
                            
                                    <table class="table table-bordered table-hover table-responsive" style="font-size:12px;">
                            <tr>
                                <th>Total premium charged</th><td style="text-align: right;" id="total_th">Loading...</td>
                            </tr>
                            <tr>
                            <th>Total premium paid</th><td style="text-align: right;" id="payment_th" >Loading...</td>
                            </tr>

                                     <tr>
                                    <th>Total outstanding balance</th><td style="text-align: right;" id="show_balance_th" >Loading...</td>
                                    </tr>

                                     <tr>
                                    <th>Credit on account</th><td style="text-align: right;" id="credit_th" >Loading...</td>
                                    </tr>
                    </table>
                        
                        </li>
                         
                        </ul>
                    </li>
                   
                  
                 
                    
                
                
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
             <!--<div class="legal">
                <div class="copyright">
                    &copy; 2018 <a href="#"> Denkim Insurance </a>.
                </div>
                <div class="version">
                    <b>Version: </b> 1.1.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        
    </section>


    <section class="content">
        <div class="container-fluid">
          <!-- #END# Select -->
		  
		  
		  
		  
		  
		  
		   <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              <?php echo $message;?> 
                            </h2>
                           
                        </div>
                        <div class="body">
						
						<br>  
        <?php echo $list;?><br>
						
						
						
						
						
                        </div>
                    </div>
                </div>
		   
		  
		  
              
		   
		   
		   
		   
		   
		   
		   
		   
        
         
            <!--#END# DateTime Picker -->
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="plugins/node-waves/waves.js"></script>

    <!-- Autosize Plugin Js -->
    <script src="plugins/autosize/autosize.js"></script>

    <!-- Moment Plugin Js -->
    <script src="plugins/momentjs/moment.js"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <!-- Custom Js -->
    <script src="js/admin.js"></script>
    <script src="js/pages/forms/basic-form-elements.js"></script>

    <!-- Demo Js -->
    <script src="js/demo.js"></script>
</body>
</html>
