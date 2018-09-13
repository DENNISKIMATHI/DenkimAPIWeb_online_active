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




if(isset($_GET['pn']) && !empty($_GET['pn']) )
{   
        $type_is=12;//type
        
        $policy_number=trim($_GET['pn']);
        $action_page='public_liability_insurance_select.php?pn='.$policy_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/policies_select/public_liability_insurance_select.php');

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        if($check_is==true)//if check is true
        {
            $company_name=$message_is['company_name'];
            $premium_percentage=$message_is['premium_percentage'];
            $minimum=$message_is['minimum'];
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
            $message='<span id="bad_upload_message">'.$message_is.'</span>';
        }
        
        
        //submit
        if(isset($_POST['limit']) && !empty($_POST['limit']) )
        {
            $limit=trim($_POST['limit']);
            
            
            $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
            //get id
            $item_id=make_cart_item_id($type_is,$how_many);
            //assign
            $items_array_is=array('policy_number'=>$policy_number,
                                'limit'=>$limit,
                                'company_name'=>$company_name,
                                'premium_percentage'=>$premium_percentage,
                                'minimum'=>$minimum,
                                'policy_number'=>$policy_number,
                                'expiry_duration_days'=>$expiry_duration_days,
                                'logo_url'=>$logo_url,
                                'html_url'=>$html_url,
                                'time_stamp'=>$time_stamp
                                );
            
            $_SESSION['shoping_cart'][$type_is][$how_many]=array($item_id=>$items_array_is);
            
            //decide what to do
            header('location: ../shopping_kart?message=Policy added to cart&type=1');//
        }
        
        
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Public liability insurance select</title>
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
                                    <h2><?php echo $company_name;?></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
		 <img src="<?php echo $logo_url;?>"/>
		 
        <?php echo $message;?><br>
		
           <form action="<?php echo $action_page;?>" method="post">
               <h2>Enter the limit.(Minimum is: <?php echo number_format($minimum,2);?>)</h2>
            <input type="number" min="<?php echo $minimum;?>" required name="limit" premium_percentage_is="<?php echo $premium_percentage;?>" id="limit" placeholder="Limit"/>
            <br>
            <br>
            
            <b>Premium</b><br>
            <br>
            <span id="premium_span"></span>
                
                
  </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Proceed and buy</button>
        </form>
        <script type="text/javascript" src="../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../javascript/public_liability_insurance_select.js"></script>
                            
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