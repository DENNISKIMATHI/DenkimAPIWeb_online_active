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

if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )
{//if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        
        $action_page="wallet_withdraw.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for form submission
        $full_link="wallet_withdraw.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for form submission
        $link_without_sort_column_sort_order="wallet_withdraw.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for headers sorting
        $link_without_limit_skip_rows_every="wallet_withdraw.php";//for browsing
        
       
        
            $account_number=0;
                $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_accident_insurance.php');
                $email_address=$personal_details_array['email_address'];
                $phone_number=$personal_details_array['phone_number'];

            
            
            
            //form submission
        if(isset($_POST['headers_is']) && !empty($_POST['headers_is']) &&
                 is_numeric($_POST['headers_is']) && 
                isset($_POST['limit_is']) && !empty($_POST['limit_is']) && 
                is_numeric($_POST['limit_is']) && 
                ( $_POST['skip_is']==0 || is_numeric($_POST['skip_is']) ))
        {
            $new_limit=trim($_POST['limit_is']);
            $new_skip=trim($_POST['skip_is']);
            $new_rows_every=trim($_POST['headers_is']);
            
             header('location: '.$link_without_limit_skip_rows_every.'?l='.$new_limit.'&s='.$new_skip.'&re='.$new_rows_every.' ');//redirect back to form correctly
        }
             //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserWallet";

        $myvars='session_key='.$_SESSION['session_key'].'&limit='.$limit.'&skip='.$skip.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/wallet.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
           $count=$skip;//make count skipped rows
            
            $total_for_table_rows=$skip+$limit;//total for table highlight js function
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
                  
                  
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                  
                  
                  if(count($debit)==0)
                  {
                            $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                      <td>'.($count+1).'</td>  
                                                                      <td id="mode_of_payment_td'.$count.'" >'.$mode_of_payment.'</td>
                                                                      <td id="camount_paid_td'.$count.'" >'.number_format($amount_paid).'</td>

                                                                      <td id="transaction_code_td'.$count.'" >'.$transaction_code.'</td>
                                                                      <td id="use_date_td'.$count.'" >'.return_simple_date_function(strtotime($use_date)*1000).'</td>
                                                                      <td id="time_date_of_payment_td'.$count.'" >'.$time_date_of_payment.'</td>
                                                                      <td id="action_td'.$count.'" ><a href="#">Withdraw</a></td>
                                                      </tr>';
                                $table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header

                                $count++;
                                $from_one_counter++;
                  }
                  
                 
                   
                 
                  
            }//end of foreach $message_is as $value
            
            $table='<table>'.$table_head.$table.'
                         </table>';
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
            if($message_is=='')
            {
                header('location: ../mobile_logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
                $message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
           // header('location: logs_view.php?c='.$_GET['c'].'&l='.$_GET['l'].'&s='.$_GET['s'].'&message='.$message_is.'&type=2');//
        }
        
}////if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )


            


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	 <title>Wallet withdraw</title>
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
                                         
        
				
				   <h4>
                              <?php echo $company_name;?> 
							  
                                 <?php echo $message;?>
                            </h4>
		  
            <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form><br>
                           <?php echo $table;?><br>
                             <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form>
                           
              <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
		 
               
			
                                    
									
                                                                           							  
                                     
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
