<?php
require '../../le_functions/functions.php';
require '../../le_functions/sessions.php';

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



if(
        isset($_GET['s']) && !empty($_GET['s']) &&
        isset($_GET['t']) && !empty($_GET['t']) &&
        isset($_GET['pi']) && !empty($_GET['pi']) &&
        isset($_GET['pn']) && !empty($_GET['pn']) &&
        isset($_GET['cn']) && !empty($_GET['cn']) &&
        isset($_GET['pd']) && !empty($_GET['pd']) &&
        isset($_GET['edd']) && !empty($_GET['edd']) &&
        isset($_GET['pt']) && !empty($_GET['pt']) 
        
        )
{
               //fetch email
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_accident_insurance.php');
        $email_address=$personal_details_array['email_address'];
                
                $source=trim($_GET['s']);
                $type=trim($_GET['t']);
                $policy_id=trim($_GET['pi']);
                $policy_name=trim($_GET['pn']);
                $company_name=trim($_GET['cn']);
                $policy_date=trim($_GET['pd']);
                $expiry_duration_days=trim($_GET['edd']);
                $policy_total=trim($_GET['pt']);
                
               //$action_page='policy_view_payments_specific.php?s='.$source.'&t='.$type.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total;
                $return_link=$source;
                
                             
                 
                    
               //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserPayments";

        $myvars='session_key='.$_SESSION['session_key'].'&email_address='.$email_address.'&policy_id='.$policy_id;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/policy_view_payments_specific.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        
        //draw
        if($check_is==true)//if check is true
        {
             $message_is=$returned_json_decoded["message"];//message
            
                            $count=0;
                             
                             $total_payments=0;
                             foreach ($message_is as $value) 
                             {//start of foreach $message_is as $value
                                    $_id=$value['_id']['$oid'];
                                    $mode_of_payment=$value['mode_of_payment'];
                                    $amount_paid=$value['amount_paid'];
                                    $time_date_of_payment=$value['time_date_of_payment'];
                                    $transaction_code=$value['transaction_code'];
                                    $msidn=$value['msidn'];
                                    
                                    

                                   $row_color=$count%2;
                                   $row_color=$row_color==0?'odd':'even';

                                   $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                                 <td>'.($count+1).'</td>  
                                                                                 <td id="mode_of_payment_td'.$count.'" >'.strtoupper($mode_of_payment).'</td>
                                                                                 <td id="amount_paid_td'.$count.'" >'.number_format($amount_paid).'</td>
                                                                                 <td id="time_date_of_payment_td'.$count.'" >'.$time_date_of_payment.'</td>  
                                                                                 <td id="transaction_code_td'.$count.'" >'.strtoupper($transaction_code).'</td>   
                                                                                 <td id="msidn_td'.$count.'" >'.$msidn.'</td> 
                                                                                     

                                                                     </tr>';
                                   
                                   $total_payments+=$amount_paid;//add to total payments
                                   $count++;
                                   
                             }//end of foreach $message_is as $value
                             
                             
                             $balance_is=get_balance_for_polices($policy_total,$total_payments,$policy_date,$expiry_duration_days);//get balance
                             
                             //add balance and total
                             $table=$table.'<tr bgcolor="white">
                                                                 <th></th>  
                                                                                 <th>TOTAL </td>
                                                                                 <th>'.number_format($total_payments).'</th>
                                                                                 <th>Policy age</th>  
                                                                                 <th>'.$balance_is['policy_age_days'].' day(s)</th> 
                                                                                 <th>'.$balance_is['policy_age_years'].' year(s)</th>      
                                                                                 <th>Balance</th> 
                                                                                 <th>'.number_format($balance_is['balance']).'</th>
                                                                                 
                                                                     </tr>';
                             
                             $table_head='<tr bgcolor="white">
                                          <th>#</th>
                                              <th><a href="#"onmouseover="hover_link(\'mode_of_payment_td\',\''.$count.'\');" onmouseout="out_link(\'mode_of_payment_td\',\''.$count.'\');" >Mode of payment</a></th>
                                             <th><a href="#"onmouseover="hover_link(\'amount_paid_td\',\''.$count.'\');" onmouseout="out_link(\'amount_paid_td\',\''.$count.'\');" >Amount(KES. )</a></th>
                                              <th><a href="#" onmouseover="hover_link(\'time_date_of_payment_td\',\''.$count.'\');" onmouseout="out_link(\'time_date_of_payment_td\',\''.$count.'\');" >Date</a></th>
                                             <th><a href="#" onmouseover="hover_link(\'transaction_code_td\',\''.$count.'\');" onmouseout="out_link(\'transaction_code_td\',\''.$count.'\');" >Transaction code</a></th>
                                             <th><a href="#" onmouseover="hover_link(\'msidn_td\',\''.$count.'\');" onmouseout="out_link(\'msidn_td\',\''.$count.'\');" >Phone number</a></th>
                                             
                                             </tr>';
                             
                             $table='<table>'.$table_head.$table.'
                                          </table>';
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
            // $message=$message_is;
           // header('location: logs_view.php?c='.$_GET['c'].'&l='.$_GET['l'].'&s='.$_GET['s'].'&message='.$message_is.'&type=2');//
            if($message_is=='')
            {
                header('location: ../mobile_logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
               
                         header('location: '.$return_link.'?message='.$message_is.'&type=2');
                //$message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
        }
                
         $payments_link='policy_make_payments_specific.php?s='.$source.'&t='.$type.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&pt='.$balance_is['balance'];
                 $full_make_payments_link='<a style="background-color:green" href="'.$payments_link.'" title="Make '.$policy_name.' payments" class="btn btn-block btn-lg btn-warning waves-effect">Make payment</a>';
                                   
                
                
}               



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <title>Policy payments specific</title>
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
                              <?php echo $policy_name;?>
                                
                            </h2>
                          
                          
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                            <img src="<?php echo $logo_url;?>"/>
        
				
				   <h4>
                              <?php echo $company_name;?> 
							  
                                 <?php echo $message;?>
                            </h4>
		  
           
               
			
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
												
											<?php echo $table;?><br>
											
											<?php echo $full_make_payments_link;?>
								  
                                                </div>
                                            </div>
                                        </div>
									
									 
									
                                                                           							  
                                     
                                    </div>
									
									      
											
				
					
          <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>

                                
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
