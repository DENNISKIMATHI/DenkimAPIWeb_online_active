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

if(isset($_POST['old_password']) && !empty($_POST['old_password']) && isset($_POST['new_password']) && !empty($_POST['new_password']) & isset($_POST['confirm_password']) && !empty($_POST['confirm_password']))
{
    $old_password=trim($_POST['old_password']);
    $new_password=trim($_POST['new_password']);
    $confirm_password=trim($_POST['confirm_password']);
    
     $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorChangePassword";
       
        $myvars='session_key='.$_SESSION['session_key'].'&old_password='.$old_password.'&new_password='.$new_password.'&confirm_password='.$confirm_password;
        
        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/claim_handlers/change_password.php');
       
        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        $message_is=$returned_json_decoded["message"];//message
         
        if($check_is==true)//if check is true
        {
            
            header('location: ../?message='.$message_is.'. Please log in with your new password.&type=1');//
            
        }
        else//else failed
        {
            //$message=$message_is;
            header('location: change_password.php?message='.$message_is.'&type=2');//
        }
}
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <title>Change password</title>
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
                <a class="navbar-brand" href="../claim_handlers/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Claim handler</div>
                    <div class="email">Administrator</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons">account_box</i>View Profile</a></li>
                            <li role="seperator" class="divider"></li>
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
                       <a href="../claim_handlers/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="clients.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete clients, view their policies, select, delete and edit policies for them, create and delete claims, also make, veiw, edit and delete payments">
                            <i class="material-icons">contacts</i>
                            <span>Clients</span>
                        </a>
                    </li>
                    <li>
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/claim_handlers/*');?></a>
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
                                    <h2>Change Password</h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
		 <?php echo $message;?><br>
         <form action="" method="POST">
             <input type="password" name="old_password" placeholder="Current password"/>
             <input type="password" name="new_password" placeholder="New password"/>
              <input type="password" name="confirm_password"  placeholder="Confirm password"/>
            
           <button type="submit" class="btn btn-primary m-t-15 waves-effect">Update</button>
         </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
		 <a href="../claim_handlers/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 	  
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