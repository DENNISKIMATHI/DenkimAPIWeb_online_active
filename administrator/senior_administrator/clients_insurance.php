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










if(  isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) &&  isset($_GET['sc']) && !empty($_GET['sc']) && isset($_GET['so']) && !empty($_GET['so']) && isset($_GET['re']) && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f']) && isset($_GET['p']) && !empty($_GET['p']))
{
	$limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $sort_column=trim($_GET['sc']);
        $sort_order=trim($_GET['so']);
        $rows_every=trim($_GET['re']);
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $phone_number=trim($_GET['p']);
        
        
        $full_link="clients_insurance.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&p=".$phone_number;//for form submission
        $return_link="clients.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $view_argument="?e=".$email_address."&f=".$full_names."&p=".$phone_number;
       
       
}

    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
          <title>Insurance policies</title>
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
					<a href="upload_html_logo.php" title="Upload logo and html for your policies to the file server">
                           <i class="material-icons">attachment</i>
                            <span>File Server</span>
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
            <h4>Motor insurance: <a href="./view_user_motor_insurance.php<?php echo $view_argument;?>" title="View Motor insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_motor_insurance.php<?php echo $view_argument;?>" title="Select Motor insurance">Select</a></h4>
            <h4>In patient medical insurance: <a href="./view_user_in_patient_medical_insurance.php<?php echo $view_argument;?>" title="View and select In patient medical insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_in_patient_medical_insurance.php<?php echo $view_argument;?>" title="Select In patient medical insurance">Select</a></h4>
            <h4>Accident insurance: <a href="./view_user_accident_insurance.php<?php echo $view_argument;?>" title="View and select Accident insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_accident_insurance.php<?php echo $view_argument;?>" title="Select Accident insurance">Select</a></h4>
            <h4>Contractors all risk insurance: <a href="./view_user_contractors_all_risk_insurance.php<?php echo $view_argument;?>" title="View and select Contractors all risk insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_contractors_all_risk_insurance.php<?php echo $view_argument;?>" title="Select Contractors all risk insurance">Select</a></h4>
            <h4>Performance Bond insurance: <a href="./view_user_performance_bond_insurance.php<?php echo $view_argument;?>" title="View and select Performance Bond insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_performance_bond_insurance.php<?php echo $view_argument;?>" title="Select Performance Bond insurance">Select</a></h4>
            <h4>Fire burglary theft insurance: <a href="./view_user_fire_burglary_theft_insurance.php<?php echo $view_argument;?>" title="View and selectFire burglary theft insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_fire_burglary_theft_insurance.php<?php echo $view_argument;?>" title="Select Fire burglary theft insurance">Select</a></h4>
            <h4>Home insurance: <a href="./view_user_home_insurance.php<?php echo $view_argument;?>" title="View and select Home insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_home_insurance.php<?php echo $view_argument;?>" title="Select Home insurance">Select</a></h4>
            <h4>Maternity insurance: <a href="./view_user_maternity_insurance.php<?php echo $view_argument;?>" title="View and select Maternity insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_maternity_insurance.php<?php echo $view_argument;?>" title="Select Maternity insurance">Select</a></h4>
            <h4>Dental insurance: <a href="./view_user_dental_insurance.php<?php echo $view_argument;?>" title="View and select Dental insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_dental_insurance.php<?php echo $view_argument;?>" title="Select Dental insurance">Select</a></h4> 
            <h4>Optical insurance: <a href="./view_user_optical_insurance.php<?php echo $view_argument;?>" title="View and select Optical insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_optical_insurance.php<?php echo $view_argument;?>" title="Select Optical insurance">Select</a></h4> 
            <h4>Out patient medical insurance: <a href="./view_user_out_patient_medical_insurance.php<?php echo $view_argument;?>" title="View and select Out patient medical insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_out_patient_medical_insurance.php<?php echo $view_argument;?>" title="Select Out patient medical insurance">Select</a></h4>
            
            <h4>Public liability insurance: <a href="./view_user_public_liability_insurance.php<?php echo $view_argument;?>" title="View and select Public liability insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_public_liability_insurance.php<?php echo $view_argument;?>" title="Select Public liability insurance">Select</a></h4>
            <h4>Goods in transit insurance: <a href="./view_user_goods_in_transit_insurance.php<?php echo $view_argument;?>" title="View and select Goods in transit insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_goods_in_transit_insurance.php<?php echo $view_argument;?>" title="Select Goods in transit insurance">Select</a></h4>
            <h4>Product liability insurance: <a href="./view_user_product_liability_insurance.php<?php echo $view_argument;?>" title="View and select Product liability insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_product_liability_insurance.php<?php echo $view_argument;?>" title="Select Product liability insurance">Select</a></h4>
            <h4>Motor psv insurance for uber, taxify, little cab, institutional buses and vans: <a href="./view_user_motor_psv_uber_insurance.php<?php echo $view_argument;?>" title="View and select Motor psv insurance for uber, taxify, little cab, institutional buses and vans.">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_motor_psv_uber_insurance.php<?php echo $view_argument;?>" title="Select Motor psv insurance for uber, taxify, little cab, institutional buses and vans.">Select</a></h4>
            <h4>Wiba and employers liability insurance: <a href="./view_user_wiba_and_employers_insurance.php<?php echo $view_argument;?>" title="View and select Wiba and employers liability insurance">View</a>&nbsp;&nbsp;&nbsp;<a href="./select_user_wiba_and_employers_insurance.php<?php echo $view_argument;?>" title="Select Wiba and employers liability insurance">Select</a></h4>
            
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