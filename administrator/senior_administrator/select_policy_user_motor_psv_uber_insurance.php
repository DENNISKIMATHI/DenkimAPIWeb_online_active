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




if(isset($_GET['pn']) && !empty($_GET['pn']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f'])  && isset($_GET['p']) && !empty($_GET['p']))
{   
        $type_is=15;//type
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $policy_number=trim($_GET['pn']);
                   $phone_number=trim($_GET['p']);
        
        $action_page='select_policy_user_motor_psv_uber_insurance.php?pn='.$policy_number.'&e='.$email_address.'&f='.$full_names.'&p='.$phone_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/senior_administrator/select_policy_user_motor_psv_uber_insurance.php');

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        if($check_is==true)//if check is true
        {
            $company_name=$message_is['company_name'];
            
            $v_percentage=$message_is['v_percentage'];
            $n_percentage=$message_is['n_percentage'];
            $minimum_excess_protector=$message_is['minimum_excess_protector'];
            $minimum_political_violence=$message_is['minimum_political_violence'];
            $excess_protector_multiplier=$message_is['excess_protector_multiplier'];
            $political_violence_multiplier=$message_is['political_violence_multiplier'];
            $aa_constant=$message_is['aa_constant'];
            
            $policy_number=$message_is['policy_number'];
            $expiry_duration_days=$message_is['expiry_duration_days'];
            $logo_url=$message_is['logo_url'];
            $html_url=$message_is['html_url'];
            $time_stamp=$message_is['time_stamp'];
            
            //request html_content
            $html_content=  send_curl_post($html_url, null, null);
        }
        else
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
        
        
       
        
        
          //submit
        if(isset($_POST['vehicle_value']) && !empty($_POST['vehicle_value']) &&
           isset($_POST['vehicle_registration_details']) && !empty($_POST['vehicle_registration_details']) &&
                isset($_POST['number_of_passengers']) && !empty($_POST['number_of_passengers'])
                )
        {
            $vehicle_value=trim($_POST['vehicle_value']);
            $vehicle_registration_details=trim($_POST['vehicle_registration_details']);
            $number_of_passengers=trim($_POST['number_of_passengers']);
            $excess_protector_percentage=trim($_POST['excess_protector_percentage']);
            $political_risk_terrorism_percentage=trim($_POST['political_risk_terrorism_percentage']);
            $aa_membership=trim($_POST['aa_membership']);
            
            $excess_protector_percentage_boolean=$excess_protector_percentage==1? 'true': 'false';
            $political_risk_terrorism_percentage_boolean=$political_risk_terrorism_percentage==1? 'true': 'false';
            $aa_membership_boolean=$aa_membership==1? 'true': 'false';
            //echo $insured_item_value.'--'.$excess_protector_percentage_is_boolean.'--'.$political_risk_terrorism_percentage_is_boolean.'--'.$aa_membership_is_boolean.'<hr>';
            
            $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
            //get id
            $item_id=make_cart_item_id($type_is,$how_many);
            //assign
            $items_array_is=array('policy_number'=>$policy_number,
                
                                'vehicle_value'=>(int)$vehicle_value,
                                'vehicle_registration_details'=>$vehicle_registration_details,
                                'number_of_passengers'=>(int)$number_of_passengers,
                                'excess_protector_percentage'=>$excess_protector_percentage_boolean,
                                'political_risk_terrorism_percentage'=>$political_risk_terrorism_percentage_boolean,
                                'aa_membership'=>$aa_membership_boolean,
                
                                'company_name'=>$company_name,
                
                                'v_percentage'=>$v_percentage,
                                'n_percentage'=>$n_percentage,
                                'minimum_excess_protector'=>$minimum_excess_protector,
                                'minimum_political_violence'=>$minimum_political_violence,
                                'excess_protector_multiplier'=>$excess_protector_multiplier,
                                'political_violence_multiplier'=>$political_violence_multiplier,
                                'aa_constant'=>$aa_constant,
                                
                                
                                'expiry_duration_days'=>$expiry_duration_days,
                                'logo_url'=>$logo_url,
                                'html_url'=>$html_url,
                                'time_stamp'=>$time_stamp
                                );
            
           $array_kart=array();
            $array_kart[$type_is][0]=array($item_id=>$items_array_is);
            
              check_out_kart_with_email($array_kart,$email_address,'/senior_administrator/select_policy_user_motor_psv_uber_insurance.php');
            //decide what to do
            header('location: view_user_motor_psv_uber_insurance.php?message=Policy selected&e='.$email_address.'&f='.$full_names.'&type=1&p='.$phone_number.'');//
        }
        
        
        
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
          <title>Motor psv, uber, taxify, little cab and institutional buses and vans insurance select</title>
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
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2><?php echo $full_names;?></h2>
								<h2><?php echo $company_name;?></h2>
									
                                </div>
                               
                            </div>
                       
                        </div>
                        <div class="body">
						 <img src="<?php echo $logo_url;?>"/>
		 <?php echo $message;?><br>
           <form action="<?php echo $action_page;?>" method="post">
            <h2>Enter the value of the vehicle.</h2>
            <input type="number" min="1" name="vehicle_value" required v_percentage="<?php echo $v_percentage;?>" n_percentage="<?php echo $n_percentage;?>"  id="vehicle_value" placeholder="Vehicle value"/>
            <br>
            
            <h2>Enter the number of passengers.</h2>
            <input type="number" min="1" name="number_of_passengers"  id="number_of_passengers"  required v_percentage="<?php echo $v_percentage;?>" n_percentage="<?php echo $n_percentage;?>" placeholder="Number of passengers"/>
            <br>
            
            <h2>Enter the vehicle registration details.</h2>
            <textarea name="vehicle_registration_details" required placeholder="Vehicle registration details"></textarea>
            <br>
            
            
            
            <b>Premium</b><br>
            <br>
            <span id="premium_span"></span>
                
                <h2>See What's Covered</h2>
                <?php echo $html_content;?>
                <h2>Additional benefits</h2><br><br>
                    <div class="switch">
                Excess protector: <label><input type="checkbox" minimum_excess_protector="<?php echo $minimum_excess_protector;?>" excess_protector_multiplier="<?php echo $excess_protector_multiplier;?>" status_is="unchecked" name="excess_protector_percentage" id="excess_protector_percentage" value="1"><span class="lever"></span></label><span id="excess_protector_percentage_is_span"></span><br><br>
                Political Risk/terrorism: <label><input type="checkbox" minimum_political_violence="<?php echo $minimum_political_violence;?>" political_violence_multiplier="<?php echo $political_violence_multiplier;?>" status_is="unchecked"  name="political_risk_terrorism_percentage" id="political_risk_terrorism_percentage" value="1" ><span class="lever"></span></label> <span id="political_risk_terrorism_percentage_is_span"></span><br><br>
                AA Membership/Rescue: <label><input type="checkbox" name="aa_membership" id="aa_membership" value="1"  aa_constant="<?php echo $aa_constant;?>"  ><span class="lever"></span></label><span id="premium_span"><?php echo 'KES. '.number_format($aa_constant) ?></span><br><br>
  </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Proceed and buy</button>
        </form>
        <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../../javascript/motor_psv_uber_insurance_select.js"></script>
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