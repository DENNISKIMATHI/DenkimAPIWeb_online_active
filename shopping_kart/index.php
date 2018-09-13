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

echo json_encode($_SESSION['shoping_cart']).'<hr>';
if(!empty($_SESSION['shoping_cart']))
{
    $list='';
    $shopping_kart=$_SESSION['shoping_cart'];
    foreach ($shopping_kart as $key => $value) 
    {//start of foreach ($shopping_kart as $value) 
        $type=$key;
        $kart_item_array=$value;
        switch ($type) 
        {//start of switch $type
                case 1://Motor insurance
                    
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_motor($value);
                        $html=$item['html'];
                        $item_id=$item['item_id'];
                        $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'"  class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                        
                        $list.='<li><h2>Motor insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                    }
                    
                break;
                
                case 2://In patient medical insurance
                    
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_in_patient_medical($value);
                        $html=$item['html'];
                        $item_id=$item['item_id'];
                        $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                        
                        $list.='<li><h2>In patient insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                    }

                break;
                
                case 3://Accident insurance
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_accident($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                            
                            $list.='<li><h2>Accident insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }

                break;
            
                case 4://Contractors all risk insurance

                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_contractors_all_risk($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                            
                            $list.='<li><h2>Contractors all risk insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }
                break;
            
                case 5://Performance Bond insurance
                        
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_performance_bond_insurance($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                            
                            $list.='<li><h2>Perfomance bond insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }

                break;
            
                case 6://Fire burglary theft insurance
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_fire_burglary_theft_insurance($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                            
                            $list.='<li><h2>Fire burglary theft insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }

                break;
            
                case 7://Home insurance

                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_home_insurance($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                            
                            $list.='<li><h2>Home insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }
                break;
                
                case 8://Maternity insurance
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_maternity_medical($value);
                        $html=$item['html'];
                        $item_id=$item['item_id'];
                        $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                        
                        $list.='<li><h2>Maternity insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                    }

                break;
            
                case 9://Dental insurance 
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_dental_medical($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';

                            $list.='<li><h2>Dental insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }

                break;
            
                case 10://Optical insurance 
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_optical_medical($value);
                            $html=$item['html'];
                            $item_id=$item['item_id'];
                            $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';

                            $list.='<li><h2>Optical insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                        }

                break;
            
                case 11://out patient insurance  
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_out_patient_medical($value);
                        $html=$item['html'];
                        $item_id=$item['item_id'];
                        $remove_link='<span id="red_text_span"><a href="remove_kart_item.php?item_id='.$item_id.'" class="btn btn-primary m-t-15 waves-effect">Remove</a></span>';
                        
                        $list.='<li><h2>Out patient insurance</h2>'.$html.$remove_link.'</li>';//concatnate
                    }

                break;
            
                default:
                break;
        }//end of switch $type
    }//$shopping_kart as $key => $value
    
    $list='<ol id="shopping_kart_list">'.$list.'</ol>';
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
        <title>Shopping cart</title>
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
           <a href="./checkout.php" title="Checkout with your email address or by creating a new account" class="btn btn-primary m-t-15 waves-effect">Checkout</a>
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