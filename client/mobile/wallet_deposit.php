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


        
        $action_page="wallet_deposit.php?empty=none";//for form submission
        $full_link="wallet_deposit.php?empty=none";//for form submission
        $link_without_sort_column_sort_order="wallet_deposit.php?empty=none";//for headers sorting
        $link_without_limit_skip_rows_every="wallet_deposit.php";//for browsing
        
       
        
            $account_number=0;
                $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/view_user_accident_insurance.php');
                $email_address=$personal_details_array['email_address'];
                $phone_number=$personal_details_array['phone_number'];

            if(isset($_POST['total']) && !empty(isset($_POST['total'])) &&
            isset($_POST['phone_number']) && !empty(isset($_POST['phone_number']))  &&
            isset($_POST['use_date']) && !empty(isset($_POST['use_date'])) 
                  )
            {
              $total=$_POST['total'];
              $phone_number=$_POST['phone_number'];
              $use_date=$_POST['use_date'];


               //fetch account number
                    $url_stk_make_acc="https://www.denkiminsurance.com/client/request_acc_number/index.php";

                    $myvars_are='session='.$_SESSION['session_key'].
                            '&authorization='.api_key_is().
                            '&cookie='.$_SESSION['cookie'].
                            '&email='.$email_address.
                            '&policy_id='.$policy_id.
                            '&use_date='.$use_date.
                            '&total='.$total;

                    $header_array_is= array();

                    $account_number=send_curl_post($url_stk_make_acc,$myvars_are,$header_array_is);//cap output

            }

            //do stk push
            if(isset($_POST['total']) && !empty(isset($_POST['total'])) &&
            isset($_POST['phone_number']) && !empty(isset($_POST['phone_number']))  &&
            isset($_GET['account_number']) && !empty(isset($_GET['account_number'])) 
                  )
            {
              $total=$_POST['total'];
              $phone_number=$_POST['phone_number'];
              $account_number=$_GET['account_number'];

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
            
            
            
            



            


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	 <title>Wallet deposit</title>
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
                            </h4> <br>
							
							
						       
                     <?php
                     if(isset($_POST['total']) && !empty(isset($_POST['total'])) &&
                    isset($_POST['phone_number']) && !empty(isset($_POST['phone_number']))  &&
                   (  isset($_POST['use_date']) && !empty(isset($_POST['use_date'])) ||  isset($_GET['use_date']) && !empty(isset($_GET['use_date']))  )
                          )
                    {//if($account_number!=0)
                         
                         $use_date=isset($_POST['use_date'])?$_POST['use_date']:$_GET['use_date'];
                         ?>
                           
                                <h4>Deposit from your M-PESA</h4>
                                <li>Paybill: 906238</li>
                                <li>Account number: <?php echo $account_number?></li>
                                <li>Day you want to withdraw or use the money: <?php
                                            //echo $account_number
                                            echo return_date_function( (strtotime($use_date)*1000));    
                                        ?></li>
                                <form action="<?php echo $action_page?>&account_number=<?php echo $account_number?>&use_date=<?php echo $use_date?>" method="POST">
                                    Amount<input  type="number" class="form-control" value="<?php echo round($_POST['total'], 0, PHP_ROUND_HALF_UP);?>" name="total" min="1" max="70000"><br>
                                    M-PESA mobile number(2547XXXXXXX)<input type="number" class="form-control" value="<?php echo $_POST['phone_number'];?>" name="phone_number"><br>
                                    <input type="submit" value="Pay direct">
                                </form>
                                 <hr>
                   <br>
                   <form action="wallet_first.php" method="POST"><input type="submit" value="Confirm"></form>
                         
                            
                         <?php
                     }//if($account_number!=0)
                     else
                     {//if($account_number!=0)
                            ?>
                            
                                <h4>Deposit from your M-PESA</h4>
                                <form action="<?php echo $action_page?>" method="POST">
                                    Enter amount to deposit<input type="number"  class="form-control" required name="total" min="1" max="70000"><br>
                                    Enter M-PESA mobile number(2547XXXXXXX)<input required type="number"  class="form-control"value="<?php echo $phone_number;?>" name="phone_number"><br>
                                    Enter the day you want to withdraw or use the money<br><input type="date" class="form-control" value="<?php echo return_date_actual_function( (1000* time()) )?>" name="use_date" required  min="<?php echo return_date_actual_function( (1000* time()) )?>" > <br><br>
                                    <input type="submit" value="Deposit">
                                </form>
                               
                         
                           
                         <?php
                     }//if($account_number!=0)
                     
                     
                     ?>
                    
                   
                
									
                                                                           							  
                                     
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
