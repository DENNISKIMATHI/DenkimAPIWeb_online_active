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



if(isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f']) && isset($_GET['p']) && !empty($_GET['p']))
{
                $email_address=trim($_GET['e']);
                $full_names=trim($_GET['f']);
                   $phone_number=trim($_GET['p']);
                $type=11;
                //fetch
            $returned_json_decoded= fetch_policy_user_type($type,$email_address,$_SESSION['session_key'],$_SESSION['cookie'],'/senior_administrator/view_user_out_patient_medical_insurance.php');

            $check_is=$returned_json_decoded["check"];//check

            $message_is=$returned_json_decoded["message"];//message


            if($check_is==true)//if check is true
            {

                foreach ($message_is as $value) 
                {
                    //echo json_encode($value).'<hr>';
                    
                    $policy_number=$value['policy_number'];
                    
                    $policy_info= fetch_policy_type_specific($type,$policy_number,'/senior_administrator/view_user_out_patient_medical_insurance.php');
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
                            $array_to_print['fathers_array']=$value['selected_father_insurance'];
                            $array_to_print['mothers_array']=$value['selected_mother_insurance'];
                            $array_to_print['childrens_array']=$value['selected_children_insurance'];
                            $array_to_print['active_status']=$value['active_status'];
                            $array_to_print['selected_policy_time_stamp']=$value['time_stamp'];
                            $array_to_print['company_name']=$policy_infomessage_is['company_name'];
                            $array_to_print['father_insurance']=$policy_infomessage_is['father_insurance'];
                            $array_to_print['mother_insurance']=$policy_infomessage_is['mother_insurance'];
                            $array_to_print['children_insurance']=$policy_infomessage_is['children_insurance '];
                            $array_to_print['expiry_duration_days']=$policy_infomessage_is['expiry_duration_days'];
                            $array_to_print['logo_url']=$policy_infomessage_is['logo_url'];
                            $array_to_print['html_url']=$policy_infomessage_is['html_url'];
                            $array_to_print['company_time_stamp']=$policy_infomessage_is['time_stamp'];
                            
                            
                
                            $returned_array=make_out_patient_medical_view($array_to_print);
                            $delete_link='policy_delete_user_type_specific.php?_id='.$policy_id.'&e='.$email_address.'&f='.$full_names.'&p='.$policy_number.'&s=view_user_out_patient_medical_insurance.php&phn='.$phone_number;
                            $full_delete_link='<span id="red_text_span"><a href="'.$delete_link.'" title="Delete '.$policy_number.' for '.$full_names.'">Delete</a></span>';
                           //activate
                            $activate_deactivate_link='policy_activate_deactivate.php?s=view_user_out_patient_medical_insurance.php&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$returned_array['policy_id'].'&as='.$returned_array['status'].'&cn='.$returned_array['company_name'].'&p='.$phone_number.'&ex='.$returned_array['expiry_duration_days'];
                            $active_deactive_word=$returned_array['status']=='active'? 'Deactivate': 'Activate';
                            
                            $full_link='<a href="'.$activate_deactivate_link.'" title="'.$active_deactive_word.' '.$returned_array['policy_number'].' for '.$full_names.'">'.$active_deactive_word.'</a>'; 
                            
                            $link_with_span=$returned_array['status']=='active'? '<span id="red_text_span">'.$full_link.'</span>': '<span id="green_text_span">'.$full_link.'</span>';
                             
                           //
                            $payments_link='policy_view_payments_specific.php?s=view_user_out_patient_medical_insurance.php&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$returned_array['policy_id'].'&pn='.$returned_array['policy_number'].'&cn='.$returned_array['company_name'].'&pd='.$returned_array['policy_date'].'&edd='.$returned_array['expiry_duration_days'].'&t='.$returned_array['total'].'&p='.$phone_number;
                            
                            $full_payments_link='<a href="'.$payments_link.'" title="View '.$returned_array['policy_number'].' payments for '.$full_names.'">Payments</a>';
                            
                            $list.=$returned_array['html'].'<br>'.$full_delete_link.'&nbsp;&nbsp;&nbsp;'.$link_with_span.'&nbsp;&nbsp;&nbsp;'.$full_payments_link;
                            //echo json_encode($array_to_print).'<hr>';
                            $check_time= time()*1000;
                            $diff_time_is=$check_time-$value['time_stamp'];
                          
                            if($diff_time_is<5000)//less than 5 seconds
                            {
                                ////login and send message
                                $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/view_user_out_patient_medical_insurance.php');
                                             
                                $message_send="Hello ".$full_names.", Out patient policy to be insured by ".$returned_array['company_name']." has been selected. The policy will be activated after payment of KES. ". number_format($returned_array['total'])." is made. Payment to be done via Denkim Insurance Portal. Click the following link to log in and pay. ".$message_is_is;

                                //send message to notify on claim
                                send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/view_user_motor_insurance.php');
                                $header_email_is="New policy ". strtoupper($company_name);
                                send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/view_user_motor_insurance.php'); 

                            }
                    }
                        
                }
                         


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

            } 
        
}


?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
       <title>View out patient medical insurance</title>
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
            <!-- CPU Usage -->
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
        <?php echo $list;?><br>

                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
			<!-- innerbody -->
   
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