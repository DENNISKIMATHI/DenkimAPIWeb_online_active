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
        isset($_GET['pn']) && !empty($_GET['pn']) &&
        isset($_GET['cn']) && !empty($_GET['cn']) &&
        isset($_GET['pd']) && !empty($_GET['pd']) &&
        isset($_GET['edd']) && !empty($_GET['edd']) &&
        isset($_GET['t']) && !empty($_GET['t'])  &&
        isset($_GET['_id']) && !empty($_GET['_id']) &&
        isset($_GET['mode_of_payment']) && !empty($_GET['mode_of_payment']) &&
        isset($_GET['amount_paid']) && !empty($_GET['amount_paid']) &&
        isset($_GET['time_date_of_payment']) && !empty($_GET['time_date_of_payment'])  &&
        isset($_GET['msidn']) && !empty($_GET['msidn']) &&
        isset($_GET['p']) && !empty($_GET['p']) 
        )
{
                $email_address=trim($_GET['e']);
                $full_names=trim($_GET['f']);
                $source=trim($_GET['s']);
                $type=trim($_GET['t']);
                $policy_id=trim($_GET['pi']);
                $policy_name=trim($_GET['pn']);
                $company_name=trim($_GET['cn']);
                $policy_date=trim($_GET['pd']);
                $expiry_duration_days=trim($_GET['edd']);
                $policy_total=trim($_GET['t']);
                $phone_number=trim($_GET['p']);
                
                $_id_is=trim($_GET['_id']);
                $mode_of_payment_is=trim($_GET['mode_of_payment']);
                $amount_paid_is=trim($_GET['amount_paid']);
                $time_date_of_payment_is=trim($_GET['time_date_of_payment']);
                $transaction_code_is=trim($_GET['transaction_code']);
                $msidn_is=trim($_GET['msidn']);
                                    
                $action_page='policy_view_payments_specific_edit.php?s='.$source.'&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total.'&_id='.$_id_is.'&mode_of_payment='.$mode_of_payment_is.'&amount_paid='.$amount_paid_is.'&time_date_of_payment='.$time_date_of_payment_is.'&transaction_code='.$transaction_code_is.'&msidn='.$msidn_is.'&p='.$phone_number;
                $return_link='policy_view_payments_specific.php?s='.$source.'&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total.'&p='.$phone_number;
                
                
                  //submit
                    if(isset($_POST['mode_of_payment']) && !empty($_POST['mode_of_payment']) && 
                    isset($_POST['amount_paid']) && !empty($_POST['amount_paid']) &&
                    isset($_POST['time_date_of_payment']) && !empty($_POST['time_date_of_payment']) &&
                    isset($_POST['transaction_code']) && !empty($_POST['transaction_code']) &&
                    isset($_POST['msidn']) && !empty($_POST['msidn']) 
                    )
                    {  
                        $mode_of_payment= trim($_POST['mode_of_payment']);
                        $amount_paid=trim($_POST['amount_paid']);
                        $time_date_of_payment=trim($_POST['time_date_of_payment']);
                        $transaction_code=trim($_POST['transaction_code']);
                        $msidn=trim($_POST['msidn']);

                        
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorEditPayment";

                            $myvars='session_key='.$_SESSION['session_key'].'&mode_of_payment='.$mode_of_payment.'&amount_paid='.$amount_paid.'&time_date_of_payment='.$time_date_of_payment.'&transaction_code='.$transaction_code.'&msidn='.$msidn.'&email_address='.$email_address.'&_id='.$_id_is;

                            $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['cookie'],'Origin:/senior_administrator/policy_view_payments_specific_edit.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {

                                  header('location: '.$return_link.'&message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: '.$return_link.'&message='.$message_is.'&type=2');//
                            } 
                       

                    }
                
                    
         
                
                
                
                
}               



?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Policy payments specific edit</title>
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
								<h2><?php echo $company_name;?></h2>
									<h2><?php echo $policy_name;?></h2>
                                </div>
                               
                            </div>
                       
                        </div>
                        <div class="body">
		             <?php echo $message;?><br>
         <form action="<?php echo $action_page;?>" method="POST">
             <input type="text" name="mode_of_payment" value="<?php echo $mode_of_payment_is;?>" placeholder="Mode of payment"/>
            <input type="number" name="amount_paid" value="<?php echo $amount_paid_is;?>" placeholder="Amount paid"/>
            <input type="date" name="time_date_of_payment"  value="<?php echo $time_date_of_payment_is;?>" placeholder="Date"/>
            <input type="text" name="transaction_code" value="<?php echo $transaction_code_is;?>"  placeholder="Transaction code"/>
             <input type="number"  name="msidn" value="<?php echo $msidn_is;?>" placeholder="254711222333"/>
             <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
         </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
			<!-- innerbody -->
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