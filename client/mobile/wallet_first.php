﻿<?php
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

	
        
       
        
            $account_number=0;
                $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_accident_insurance.php');
                $email_address=$personal_details_array['email_address'];
                $phone_number=$personal_details_array['phone_number'];

           
            
        
            $limit=9999;
            $skip=0;
             //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserWallet";

        $myvars='session_key='.$_SESSION['session_key'].'&limit='.$limit.'&skip='.$skip.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/wallet.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        $total_amount_unused=0;
        
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
          
            $total_amount_unused=0;
            
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
                  
                  
                 
                  if(count($debit)==0)
                  {
                      $total_amount_unused+=$amount_paid;
                  }
 
                  
                  
                  
                  
            }//end of foreach $message_is as $value
            
           
        }
        



            


?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <title>Wallet view</title>
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
                <a class="navbar-brand" href="../console/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                       
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons">account_box</i>View Profile</a></li>
                           <li role="seperator" class="divider"></li>
                            <li><a href="../mobile_logout.php" Title="Click here to sign out" id="logout_link"><i class="material-icons">input</i>Sign Out</a></li>
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
                        <a href="../../client/console/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                
					 <li>
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
                    
                     <li>
                        <a href="wallet_first.php" title="Add money to wallet">
                            <i class="material-icons">money</i>
                            <span>Wallet</span>
                        </a>
                    </li>
                    
					<a href="claims.php?l=10&s=0&re=10" title="View claims">
                           <i class="material-icons">attachment</i>
                            <span>Claims</span>
							<?php echo get_claims_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?>
                        </a>
                    </li> 
                   <li>
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/client/console/*');?></a>
                    </li>
                    <li>
                        <?php  
                        //fetch email
                        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/index.php');
                        $email_address=$personal_details_array['email_address'];
        
                        $total=0;
                        $payment=0;
                        $balance=0;

                        //get payment combined
                //fetch

                                    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyFetchCombinedAll";

                                    $myvars='session_key='.$_SESSION['session_key'].'&email='.$email_address;

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/index.php');

                                    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                                    $returned_json_decoded= json_decode($returned_json,true);//decode

                                    $check_is_2=$returned_json_decoded["check"];//check

                                   // echo $check_is_2.'=='.$policy_id.'<br>';
                                    if($check_is_2==true)//if check is true
                                    {//if($check_is_2==true)//if check is true
                                         $message_is_now=$returned_json_decoded["message"];//message
                                         $totals_info=get_aggregate_totals_payments_client_full_json($message_is_now);

                                         $total=$totals_info['total'];
                                        $payment=$totals_info['payment'];
                                        $balance=$totals_info['balance']==0?$totals_info['total']:$totals_info['balance'];


                                    }//if($check_is_2==true)//if check is true
                                    

                    /*
                                for ($index = 1; $index < 17; $index++) 
                                {
                                        $totals_info=get_aggregate_totals_payments_client($index,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/index.php',$email_address);

                                        $total+=$totals_info['total'];
                                        $payment+=$totals_info['payment'];
                                        $balance+=$totals_info['balance']==0?$totals_info['total']:$totals_info['balance'];
                                            //echo json_encode($totals_info).'<br>';
                                }
                     * 
                     */
                             ?>
                        <table style="font-size: 9px">
                <tr>
                    <th>Total premium charged</th><td style="text-align: right;">KES. <?php echo number_format($total);?></td>
                </tr>
                <tr>
                <th>Total premium paid</th><td style="text-align: right;">KES. <?php echo number_format($payment);?></td>
                </tr>
                <?php
                $credit=0;
                $show_balance=$balance;
                if($balance<0)
                {
                    $credit=$balance;
                    $show_balance=0;
                }
                
                        ?>
                         <tr>
                        <th>Total outstanding balance</th><td style="text-align: right;">KES. <?php echo number_format($show_balance);?></td>
                        </tr>

                         <tr>
                        <th>Credit on account</th><td style="text-align: right;">KES. <?php echo number_format($credit);?></td>
                        </tr>
                        <?php
                
                ?>
               
                
            
        </table>
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
                                    <h2><?php echo $policy_name;?></h2>
									<h2><?php echo $company_name;?></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
		   <?php echo $message;?><br>
		     
                     
                    
                   
               <div class="container-fluid">
                    <div class="block-header">
           
                        <a href="./wallet_deposit.php" title="Deposit money from your M-PESA" class="btn btn-block btn-lg btn-warning waves-effect"> Deposit money from your M-PESA</a>
                        <h4>Available balance is KES. <?php echo number_format($total_amount_unused)?></h4>
                        <a href="./wallet_withdraw.php?l=10&s=0&re=10" title="Withdraw Money to your M-PESA" class="btn btn-block btn-lg btn-warning waves-effect"> Withdraw Money to your M-PESA</a>
                        <a href="./insurance_policies.php" title="Pay for your insurance premium" class="btn btn-block btn-lg btn-warning waves-effect"> Pay for your insurance premium</a>
                        <a href="./wallet_statement.php?l=10&s=0&re=10" title="Deposit money from your M-PESA" class="btn btn-block btn-lg btn-warning waves-effect"> View Statement</a>
                    </div>
                </div>
                           
              <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
		 
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
			  <a href="../../client/console/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br>
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