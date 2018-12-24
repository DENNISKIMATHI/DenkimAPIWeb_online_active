<?php
require '../../le_functions/sessions.php';
require '../../le_functions/functions.php';

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




if(isset($_GET['pn']) && !empty($_GET['pn'])  )
{   
        $type_is=3;//type
        //fetch email
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/select_policy_user_accident_insurance.php');
        $email_address=$personal_details_array['email_address'];
        
        $policy_number=trim($_GET['pn']);
        
        $action_page='select_policy_user_accident_insurance.php?pn='.$policy_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/client/console/select_policy_user_accident_insurance.php');

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        //echo json_encode($message_is);
        if($check_is==true)//if check is true
        {
            $company_name=$message_is['company_name'];
            $options=$message_is['options'];
            $policy_number=$message_is['policy_number'];
            $expiry_duration_days=$message_is['expiry_duration_days'];
            $logo_url=$message_is['logo_url'];
            $time_stamp=$message_is['time_stamp'];
            
            $radio_buttons=make_accident_radio_buttons($options,'premium_options');
        }
        else
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
        
        $array_options=array();
        
       
        if(isset($_POST['premium_options']) && !empty($_POST['premium_options']))
        {
            $selected_option=trim($_POST['premium_options']);
            
            
            foreach ($options as $value) 
            {
                $option_name=$value['option_name'];
                $premium=$value['premium'];
                $html_url=$value['html_url'];
                
                if($option_name==$selected_option)
                {
                    $array_options[$option_name]=true;
                }
                else
                {
                    $array_options[$option_name]=false;
                }

            }
            $array_options=array($array_options);
            
            $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
                            
                                    //get id
                                    $item_id=make_cart_item_id($type_is,$how_many);
                                    //assign
                                    $items_array_is=array('policy_number'=>$policy_number,
                                                        'options'=>$options,
                                                        'selected_options'=>$array_options,
                                                        'company_name'=>$company_name,
                                                        'expiry_duration_days'=>$expiry_duration_days,
                                                        'logo_url'=>$logo_url,
                                                        'time_stamp'=>$time_stamp
                                                        );

                                    $array_kart=array();
                                $array_kart[$type_is][0]=array($item_id=>$items_array_is);

                                $details_are=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/shopping_kart/checkout_login.php');
                                 check_out_kart_with_email($array_kart,$email_address,'/client/console/select_policy_user_accident_insurance.php',$details_are['full_names']);


                                header('location: view_user_accident_insurance.php?message=Policy selected&type=1');//
        }
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Accident Insuarance</title>
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
		  
		  
		  <div class="row clearfix">
                <!-- Basic Examples -->
                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12"">
                    <div class="card">
                        <div class="header">
                            <h2>
                              <?php echo $company_name;?> 
                                
                            </h2>
                          
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                            <img src="<?php echo $logo_url;?>"/> <br><br>
        
				 <?php echo $message;?>
			
                                    <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingOne_1">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_1" aria-expanded="true" aria-controls="collapseOne_1">
                                                        
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1">
                                                <div class="panel-body">
												
                                                  <span id="premium_span"></span>
											 <form action="<?php echo $action_page;?>" method="post">
												<?php echo $radio_buttons;?><br>
												  
                                                </div>
                                            </div>
                                        </div>
                                      							  
                                     
                                    </div>
									
									      
											
											
			<button type="submit" class="btn bg-blue waves-effect"> <span>Proceed and buy </span> <i class="material-icons">send</i> 
                                </button>
        </form>
      
               <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../../javascript/accident_insurance.js"></script>
            
		   
									
                                </div>

                                
                            </div>
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
