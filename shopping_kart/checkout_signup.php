<?php
require '../le_functions/sessions.php';
require '../le_functions/functions.php';

//setting edit message
if(isset($_GET['message']) && !empty($_GET['message']) && isset($_GET['type']) && !empty($_GET['type']))
{
	$message=$_GET['message'];
        $type=$_GET['type'];
        $good_bad_id=$type==1? 'good_upload_message': 'bad_upload_message';
	$message='<span id="'.$good_bad_id.'">'.$message.'</span>';
}

//echo json_encode($_SESSION['shoping_cart']).'<hr>';
if(!empty($_SESSION['shoping_cart']))
{
   $list='';
    $shopping_kart=$_SESSION['shoping_cart'];
    $count=count($shopping_kart);
    if($count==1)
    {
        $list='<h3>'.count_the_cart($shopping_kart).'</h3>';
    }
    else 
    {
        $list='<h3>'.count_the_cart($shopping_kart).'</h3>';

    }
    
    
                    //submit
                    if(isset($_POST['national_id']) && !empty($_POST['national_id']) && 
                    isset($_POST['full_names']) && !empty($_POST['full_names']) &&
                    isset($_POST['email_address']) && !empty($_POST['email_address']) &&
                    isset($_POST['phone_number']) && !empty($_POST['phone_number']) &&
                    isset($_POST['antispam']) && !empty($_POST['antispam']) 
                    )
                    {  
                        $national_id=trim($_POST['national_id']);
                        $full_names=trim($_POST['full_names']);
                        $email_address=trim($_POST['email_address']);
                        $phone_number=trim($_POST['phone_number']);
                        $antispam=trim($_POST['antispam']);

                        if($antispam==$_SESSION['spam'])
                        {
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.CreateClient";

                            $myvars='national_id='.$national_id.'&full_names='.$full_names.'&email_address='.$email_address.'&phone_number='.$phone_number;

                            $header_array= array('Authorization:'.api_key_is(),'Origin:/shopping_kart/checkout_signup.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                    check_out_kart_with_email($shopping_kart,$email_address,'/shopping_kart/checkout_signup.php',$full_names);
                                  //clear kart
                                  unset($_SESSION['shoping_cart']);
                                  $message_is.=' Denkim will contact you on how to pay for your policies';  
                                  header('location: ./checkout_signup.php?message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: ./checkout_signup.php?message='.$message_is.'&type=2');//
                            } 
                        }
                        else
                        {
                             header('location: ./checkout_signup.php?message=Failed anti-spam test, please try again&type=2');//

                        }

                    }
    
}
else 
{
    $list='<h3>Shopping cart is empty!</h3>';
}



?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
               <title>Checkout sign up</title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="../css/main.css" />
	
    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../css/style.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="table.css">
   
    <link href="../css/themes/all-themes.css" rel="stylesheet" />
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
                <a class="navbar-brand" href="../index.php" title="Go to the main page"><img src="../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <img src="../images/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                       <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Guest</div>
                    <div class="email">User</div>
                   
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active">
                        <a href="../client/login.php" title="Go to the main page">
						<i class="material-icons">input </i>
                            
                            <span>Sign In</span>
                        </a>
                    </li>
					  <li class="active">
                        <a href="../client/signup.php" title="Go to the main page">
						<i class="material-icons">assignment</i>
                            
                            <span>Sign Up</span>
                        </a>
                    </li>
                    <li class="active">
                             <a href="../shopping_kart/index.php" title="Go to shopping cart">
						<i class="material-icons">assignment</i>
                            
                            <span><?php echo count_the_cart($_SESSION['shoping_cart']);?></span>
                        </a>
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
        <?php echo $list;?><br>
         
      
          
          <form action="" method="POST">
            <input type="email" name="email_address" placeholder="email@domain.com"/>
            <input type="text" name="full_names" placeholder="Full names"/>
            <input type="number" name="phone_number"  placeholder="254722333444"/>
            <input type="number" name="national_id"  placeholder="National ID"/><br><br>
            <img src="../le_functions/_antispam.php" id="are_you_human" /><a href="" onclick="reload_are_you_human('are_you_human');">Reload</a><br><br>
            <input type="text" id="antispam" name="antispam" placeholder="Are you human?"/>
			<br><br>
            
			 <button type="submit" class="btn btn-primary m-t-15 waves-effect">Sign up</button>
         </form>
          
      
        
        <script type="text/javascript" src="../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../javascript/are_you_huma_reload.js"></script>

	</div>
                    </div>
                </div>
            </div>
		
		
			  <a href="../index.php" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br>
            </div>
        </div>
    </section>

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Select Plugin Js -->
    <script src="../plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="../plugins/jquery-slimscroll/jquery.slimscroll.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>

    <!-- Demo Js -->
    <script src="../js/demo.js"></script>
</body>

</html>