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
//echo return_mpesa_date_function( (time()*1000) );

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
        $phone_number=$personal_details_array['phone_number'];
        
       
        
                $source=trim($_GET['s']);
                $type=trim($_GET['t']);
                $policy_id=trim($_GET['pi']);
                $policy_name=trim($_GET['pn']);
                $company_name=trim($_GET['cn']);
                $policy_date=trim($_GET['pd']);
                $expiry_duration_days=trim($_GET['edd']);
                $policy_total=trim($_GET['pt']);
                
                $action_page='policy_make_payments_specific.php?s='.$source.'&t='.$type.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&pt='.$policy_total;
                $return_link=$source;
                
                $payments_link='policy_view_payments_specific.php?s='.$source.'&t='.$type.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&pt='.$policy_total;
                            
                 
                //pay with wallet
                if(isset($_GET['mode_of_payment']) && !empty($_GET['mode_of_payment']) &&
                isset($_GET['credit_id']) && !empty($_GET['credit_id']) &&
                isset($_GET['transaction_code']) && !empty($_GET['transaction_code']) &&
                isset($_GET['msidn']) && !empty($_GET['msidn']) &&
                isset($_GET['amount_paid']) && !empty($_GET['amount_paid']) &&
                isset($_GET['particulars']) && !empty($_GET['particulars']) &&
                isset($_GET['transaction_code']) && !empty($_GET['transaction_code']))
                {
                       
                        //debit wallet first
                        $url_is_debit=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.CreateWalletDebitPaymentPolicy";

                        $myvars_debit='session_key='.$_SESSION['session_key'].'&credit_id='.$_GET['credit_id'].'&policy_id='.$policy_id.'&policy_type='.$type.'&policy_number='.$policy_name.'&company_name='.$company_name;

                        $header_array_debit= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/policy_view_payments_specific.php');

                        $returned_json_debit=send_curl_post($url_is_debit,$myvars_debit,$header_array_debit);//cap output

                        $returned_json_decoded_debit= json_decode($returned_json_debit,true);//decode

                        $check_is_debit=$returned_json_decoded_debit["check"];//check
                        
                        $message_is_debit=$returned_json_decoded_debit["message"];//message
                        
                        //draw
                        if($check_is_debit==true)//if check is true
                        {
                            //pay policy
                            $url_is_pay_policy=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.CreateDirectPayment";

                            $myvars_pay_policy='mode_of_payment='.$_GET['mode_of_payment'].'&amount_paid='.str_replace(',', '', $_GET['amount_paid']).'&particulars='.$_GET['particulars'].'&time_date_of_payment='.return_mpesa_date_function(time()*1000).'&transaction_code='.$_GET['transaction_code'].'&msidn='.$_GET['msidn'].'&email_address='.$email_address.'&policy_id='.$policy_id;

                            $header_array_pay_policy= array('Authorization:'.api_key_is(),'Origin:/client/console/policy_view_payments_specific.php');

                            $returned_json_pay_policy=send_curl_post($url_is_pay_policy,$myvars_pay_policy,$header_array_pay_policy);//cap output

                            $returned_json_decoded_pay_policy= json_decode($returned_json_pay_policy,true);//decode

                            $check_is_pay_policy=$returned_json_decoded_pay_policy["check"];//check
                            $message_is_pay_policy=$returned_json_decoded_pay_policy["message"];//message
                            
                            if($check_is_pay_policy==true)
                            {
                                 header('location: '.$payments_link.'&message='.$message_is_pay_policy.'&type=1');
                            }
                            else
                            {
                                header('location: '.$action_page.'&message='.$message_is_pay_policy.'&type=2');
                            }
                        }
                        else
                        {
                            header('location: '.$action_page.'&message='.$message_is_debit.'&type=2');
                        }
                }
                
                    
               //fetch
       //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserPayments";

        $myvars='session_key='.$_SESSION['session_key'].'&email_address='.$email_address.'&policy_id='.$policy_id;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/policy_view_payments_specific.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        $total_payments=0;
        
        //draw
        if($check_is==true)//if check is true
        {
             $message_is=$returned_json_decoded["message"];//message
            
                           
                             
                             
                             foreach ($message_is as $value) 
                             {//start of foreach $message_is as $value
                                    
                                   
                                   $total_payments+=$amount_paid;//add to total payments
                                   $count++;
                                   
                             }//end of foreach $message_is as $value
                             
                             
                            
                             
                            
        }
            $balance_is=get_balance_for_polices($policy_total,$total_payments,$policy_date,$expiry_duration_days);//get balance       
            
         //fetch account number
        $url_stk_make_acc="https://www.denkiminsurance.com/client/request_acc_number/index.php";

        $myvars_are='session='.$_SESSION['session_key'].'&authorization='.api_key_is().'&cookie='.$_SESSION['cookie'].'&email='.$email_address.'&policy_id='.$policy_id.'&total='.round($balance_is['balance'], 0, PHP_ROUND_HALF_UP);

        $header_array_is= array();

        $account_number=send_curl_post($url_stk_make_acc,$myvars_are,$header_array_is);//cap output
         
            if(isset($_POST['total']) && !empty(isset($_POST['total'])) &&
              isset($_POST['phone_number']) && !empty(isset($_POST['phone_number']))       
                    )
            {
                $total=$_POST['total'];
                $phone_number=$_POST['phone_number'];
                
                if(is_numeric($total) && $total>0  )
                {
                    $explode_number= explode('+2547', '+'.$phone_number);
                    
                    if( is_numeric($phone_number) && strlen($phone_number)==12 && strlen($explode_number[1])==8)
                    {
                         //do stk
                        $url_stk_make_stk="https://www.denkiminsurance.com/client/request_acc_number/do_stk_push.php";

                        $myvars_are_now='total='.$total.'&phone_number='.$phone_number.'&account_number='.$account_number;

                        $header_array_is_now= array();

                        $stk_do=send_curl_post($url_stk_make_stk,$myvars_are_now,$header_array_is_now);//cap output
                        
                       // echo $stk_do;
                    }
                    else
                    {
                         $message='<span id="'.$good_bad_id.'">Check phone number must be 2547XXXXXXX</span>';
                    }
                }
                else
                {
                     $message='<span id="'.$good_bad_id.'">Check payment amount</span>';
                }
            }
            
            
            
                
             
}               



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <title>Policy Make payments specific</title>
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
        
				 <?php echo $message;?>
				   <h4>
                              <?php echo $company_name;?> 
                                
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
												
												<?php echo $table;?>
								  
                                                </div>
                                            </div>
                                        </div>
									
									      <div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingOne_1">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_1" aria-expanded="true" aria-controls="collapseOne_1">
                                                       Lipa na MPESA
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1">
                                                <div class="panel-body">
																							
												       
                         <li>Paybill: 906238</li>
                         <li>Account number: <?php echo $account_number?></li>
                         <form action="<?php echo $action_page?>" method="POST">
                             Amount<input type="number"  class="form-control"value="<?php echo round($balance_is['balance'], 0, PHP_ROUND_HALF_UP);?>" name="total" min="1" max="70000"><br>
                             Mobile number(2547XXXXXXX)<input type="number" class="form-control" value="<?php echo $phone_number;?>" name="phone_number"><br>
                             <input type="submit" value="Pay direct">
                         </form>
                         <a href="<?php echo $payments_link;?>" class="btn btn-block btn-lg btn-warning waves-effect">Confirm payment</a>
                         
										
                                                </div>
                                            </div>
                                        </div>
									
									
									
									
									  <div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingOne_1">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapseOne_1" aria-expanded="true" aria-controls="collapseOne_1">
                                                       Pay with wallet 
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_1">
                                                <div class="panel-body">
												 <?php 
                        //form submission
       
                        //fetch
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserWallet";

                        $myvars='session_key='.$_SESSION['session_key'].'&limit=999&skip=0&email_address='.$email_address;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/wallet.php');

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check

                        //draw
                            if($check_is==true)//if check is true
                            {

                                $message_is=$returned_json_decoded["message"];//message

                                $count=0;//make count skipped rows

                                $total_for_table_rows=0+999;//total for table highlight js function
                                $table_head='<tr bgcolor="white">
                                             <th>#</th>
                                                 <th><a href="#"onmouseover="hover_link(\'mode_of_payment_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'mode_of_payment_td\',\''.$total_for_table_rows.'\');" >Mode of payment</a></th>
                                                <th><a href="#"onmouseover="hover_link(\'camount_paid_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'camount_paid_td\',\''.$total_for_table_rows.'\');" >Amount(KES)</a></th>

                                                <th><a href="#" onmouseover="hover_link(\'transaction_code_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'transaction_code_td\',\''.$total_for_table_rows.'\');" >Transaction code</a></th>
                                                 <th><a href="#"onmouseover="hover_link(\'use_date_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'use_date_td\',\''.$total_for_table_rows.'\');" >Use date</a></th>
                                               <th><a href="#"onmouseover="hover_link(\'time_date_of_payment_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'time_date_of_payment_td\',\''.$total_for_table_rows.'\');" >Date of payment</a></th>
                                               <th><a href="#"onmouseover="hover_link(\'action_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'action_td\',\''.$total_for_table_rows.'\');" >Action</a></th>
                                               

                                                </tr>';
                                $from_one_counter=1;//used to know how many rows are printed from one so as to append table head
                               $rows_every=10;
                                 foreach ($message_is as $value) 
                                {//start of foreach $message_is as $value
                                      $_id=$value['_id']['$oid'];

                                      $mode_of_payment=$value['mode_of_payment'];
                                      $amount_paid=$value['amount_paid'];
                                      $particulars=$value['particulars'];
                                      $time_date_of_payment=$value['time_date_of_payment'];
                                      $transaction_code=$value['transaction_code'];
                                      $use_date=$value['use_date'];
                                      $time_stamp=$value['time_stamp'];
                                      $debit=$value['debit'];
                                       $msidn=$value['msidn'];
                                      
                                      if(count($debit)==0)
                                      {//if(count($debit)==0)
                                           
                                            $row_color=$count%2;
                                            $row_color=$row_color==0?'odd':'even';

                                            $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                                          <td>'.($count+1).'</td>  
                                                                                          <td id="mode_of_payment_td'.$count.'" >'.$mode_of_payment.'</td>
                                                                                          <td id="camount_paid_td'.$count.'" >'.number_format($amount_paid).'</td>

                                                                                          <td id="transaction_code_td'.$count.'" >'.$transaction_code.'</td>
                                                                                          <td id="use_date_td'.$count.'" >'.return_simple_date_function(strtotime($use_date)*1000).'</td>
                                                                                          <td id="time_date_of_payment_td'.$count.'" >'.$time_date_of_payment.'</td>
                                                                                          <td id="action_td'.$count.'" ><a href="'.$action_page.'&mode_of_payment='.$mode_of_payment.'&credit_id='.$_id.'&transaction_code='.$transaction_code.'&msidn='.$msidn.'&amount_paid='.$amount_paid.'&particulars='.$particulars.'&transaction_code='.$transaction_code.'">Pay</a></td>
                                                                          </tr>';
                                            $table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header

                                            $count++;
                                            $from_one_counter++;
                                      }//if(count($debit)==0)
                                      
                                }//end of foreach $message_is as $value
                                echo    $table='<table>'.$table_head.$table.'
                                                 </table>';
                                }
                        
                
                ?>
												  
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
