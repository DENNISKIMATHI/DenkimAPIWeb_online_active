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




if(isset($_GET['pn']) && !empty($_GET['pn']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f']) && isset($_GET['p']) && !empty($_GET['p']))
{   
        $type_is=11;//type
        
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $policy_number=trim($_GET['pn']);
                   $phone_number=trim($_GET['p']);
        
        $action_page='select_policy_user_out_patient_medical_insurance.php?pn='.$policy_number.'&e='.$email_address.'&f='.$full_names.'&p='.$phone_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/senior_administrator/select_policy_user_out_patient_medical_insurance.php');

        $check_is=$returned_json_decoded["check"];//check

         $message_is=$returned_json_decoded["message"];//message
        
        
        if($check_is==true)//if check is true
        {
                            $company_name=$message_is['company_name'];
                            $father_insurance=$message_is['father_insurance'];
                            $mother_insurance=$message_is['mother_insurance'];
                            $children_insurance=$message_is['children_insurance '];
                            $policy_number=$message_is['policy_number'];
                            $expiry_duration_days=$message_is['expiry_duration_days'];
                            $logo_url=$message_is['logo_url'];
                            $html_url=$message_is['html_url'];
                            $time_stamp=$message_is['time_stamp'];
                            
                            $father_radio_buttons=make_medical_father_radio_buttons($father_insurance);
                            $mother_radio_buttons=make_medical_mother_radio_buttons($mother_insurance);
                            $children_radio_buttons=make_medical_children_radio_buttons_with_number_input($children_insurance);
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
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {//start of if '$_SERVER['REQUEST_METHOD'] == 'POST''
                            $fathers_array=array();
                            $count=0;
                            $any_truths=0;//check if any truth is submitted
                            //submit father
                            //echo json_encode($father_insurance);
                            foreach ($father_insurance as $value) 
                            {

                                $maximum=$value['maximum'];
                                $minimum=$value['minimum'];

                                $premium_limit_count=$value['premium_limit_count'];

                                $options=$value['options'];

                                $radio_button_name='father_'.$minimum.'_'.$maximum;

                                $selected_limit= $_POST[$radio_button_name];

                                $array=array();//
                                for ($index = 0; $index < $premium_limit_count; $index++) 
                                {

                                    $premium_name='premium_'.$index;
                                    $limit_name='limit_'.$index;

                                    $premium_value=$options[$premium_name];
                                    $limit_value=$options[$limit_name];

                                    if($limit_value==$selected_limit)
                                    {
                                        $array[$limit_value]=true;
                                        $any_truths++;//increment
                                    }
                                    else 
                                    {
                                        $array[$limit_value]=false;
                                    }

                                }

                                $fathers_array[$count]['minimum']=(int)$minimum;
                                $fathers_array[$count]['maximum']=(int)$maximum;
                                $fathers_array[$count]['options']=array($array);
                                $count++;//increment
                            }

                            $mothers_array=array();
                            $count=0;
                            //submit father
                            //echo json_encode($mother_insurance);
                            foreach ($mother_insurance as $value) 
                            {

                                $maximum=$value['maximum'];
                                $minimum=$value['minimum'];

                                $premium_limit_count=$value['premium_limit_count'];

                                $options=$value['options'];

                                $radio_button_name='mother_'.$minimum.'_'.$maximum;

                                $selected_limit= $_POST[$radio_button_name];

                                $array=array();//
                                for ($index = 0; $index < $premium_limit_count; $index++) 
                                {

                                    $premium_name='premium_'.$index;
                                    $limit_name='limit_'.$index;

                                    $premium_value=$options[$premium_name];
                                    $limit_value=$options[$limit_name];

                                    if($limit_value==$selected_limit)
                                    {
                                        $array[$limit_value]=true;
                                        $any_truths++;//increment
                                    }
                                    else 
                                    {
                                        $array[$limit_value]=false;
                                    }

                                }

                                $mothers_array[$count]['minimum']=(int)$minimum;
                                $mothers_array[$count]['maximum']=(int)$maximum;
                                $mothers_array[$count]['options']=array($array);
                                $count++;//increment
                            }

                            $childrens_array=array();
                            $count=0;
                            //submit father
                            //echo json_encode($children_insurance);
                            foreach ($children_insurance as $value) 
                            {

                                $maximum=$value['maximum'];
                                $minimum=$value['minimum'];

                                $premium_limit_count=$value['premium_limit_count'];

                                $options=$value['options'];

                                $radio_button_name='children_'.$minimum.'_'.$maximum;
                                $input_name='input_children_'.$minimum.'_'.$maximum;

                                $selected_limit= $_POST[$radio_button_name];
                                $number_of_children= $_POST[$input_name];

                                $array=array();//
                                for ($index = 0; $index < $premium_limit_count; $index++) 
                                {

                                    $premium_name='premium_'.$index;
                                    $limit_name='limit_'.$index;

                                    $premium_value=$options[$premium_name];
                                    $limit_value=$options[$limit_name];

                                    if($limit_value==$selected_limit)
                                    {
                                        $array[$limit_value]=true;
                                        $any_truths++;//increment
                                    }
                                    else 
                                    {
                                        $array[$limit_value]=false;
                                    }

                                }

                                $childrens_array[$count]['minimum']=(int)$minimum;
                                $childrens_array[$count]['maximum']=(int)$maximum;
                                $childrens_array[$count]['options']=array($array);
                                $childrens_array[$count]['number_of_children']=(int)$number_of_children;

                                $count++;//increment
                            }


                            //echo json_encode($fathers_array);
                            //echo '<hr>';
                            //echo json_encode($mothers_array);
                            //echo '<hr>';
                           // echo json_encode($childrens_array);
                            
                            if($any_truths>0)//assign if more than zero
                            {
                                     $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
                            
                                            //get id
                                            $item_id=make_cart_item_id($type_is,$how_many);
                                            //assign
                                            $items_array_is=array('policy_number'=>$policy_number,
                                                                'fathers_array'=>$fathers_array,
                                                                'mothers_array'=>$mothers_array,
                                                                'childrens_array'=>$childrens_array,
                                                                'company_name'=>$company_name,
                                                                'father_insurance'=>$father_insurance,
                                                                'mother_insurance'=>$mother_insurance,
                                                                'children_insurance'=>$children_insurance,
                                                                'policy_number'=>$policy_number,
                                                                'expiry_duration_days'=>$expiry_duration_days,
                                                                'logo_url'=>$logo_url,
                                                                'html_url'=>$html_url,
                                                                'time_stamp'=>$time_stamp
                                                                );

                                     $array_kart=array();
                                    $array_kart[$type_is][0]=array($item_id=>$items_array_is);

                                     check_out_kart_with_email($array_kart,$email_address,'/senior_administrator/select_policy_user_out_patient_medical_insurance.php');


                                    header('location: view_user_out_patient_medical_insurance.php?message=Policy selected&e='.$email_address.'&f='.$full_names.'&type=1&p='.$phone_number.'');//
                            }
                                   
                            
        }//end of if '$_SERVER['REQUEST_METHOD'] == 'POST''
        
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <title>Out patient medical insurance select</title>
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
           
                <h2>OPTIONS</h2>
                <h3>Father insurance</h3>
                <?php echo $father_radio_buttons;?>
                <h3>Mother insurance</h3>
                <?php echo $mother_radio_buttons;?>
                <h3>Children insurance</h3>
                 <?php echo $children_radio_buttons;?>
                 <h2>TOTAL PREMIUM</h2><span id="father_mother_children_total_is"></span>
                <h2>INFORMATION</h2>
                <?php echo $html_content;?>
               
                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Select</button>
        </form>
        
                     <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                     <script type="text/javascript" src="../../javascript/out_patient_medical_insurance_select.js"></script>  
                     <script type="text/javascript" src="../../javascript/out_patient_control.js"></script>  
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