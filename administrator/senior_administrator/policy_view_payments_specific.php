<?php
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



if(isset($_GET['e']) && !empty($_GET['e']) && 
        isset($_GET['f']) && !empty($_GET['f']) &&
        isset($_GET['s']) && !empty($_GET['s']) &&
        isset($_GET['t']) && !empty($_GET['t']) &&
        isset($_GET['pi']) && !empty($_GET['pi']) &&
        isset($_GET['pn']) && !empty($_GET['pn']) &&
        isset($_GET['cn']) && !empty($_GET['cn']) &&
        isset($_GET['pd']) && !empty($_GET['pd']) &&
        isset($_GET['edd']) && !empty($_GET['edd']) &&
        isset($_GET['t']) && !empty($_GET['t']) &&
        isset($_GET['p']) && !empty($_GET['p']) 
        
        )
{
                $email_address=trim($_GET['e']);
                $full_names=trim($_GET['f']);
                $source=trim($_GET['s']);
                $type=trim($_GET['t']);
                $policy_id=trim($_GET['pi']);
                $policy_name=trim($_GET['pn']);
                $company_name=trim($_GET['cn']);
                $policy_date=trim($_GET['pd']);
                $expiry_duration_days=trim($_GET['edd']);
                $policy_total=trim($_GET['t']);
                $phone_number=trim($_GET['p']);
                
                $action_page='policy_view_payments_specific.php?s='.$source.'&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total.'&p='.$phone_number;
                $return_link=$source.'&e='.$email_address.'&f='.$full_names.'&p='.$phone_number;
                $edit_page='policy_view_payments_specific_edit.php?s='.$source.'&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total.'&p='.$phone_number;
                $delete_page='policy_view_payments_specific_delete.php?s='.$source.'&t='.$type.'&e='.$email_address.'&f='.$full_names.'&pi='.$policy_id.'&pn='.$policy_name.'&cn='.$company_name.'&pd='.$policy_date.'&edd='.$expiry_duration_days.'&t='.$policy_total.'&p='.$phone_number;
                
                
                  //submit
                    if(isset($_POST['mode_of_payment']) && !empty($_POST['mode_of_payment']) && 
                    isset($_POST['amount_paid']) && !empty($_POST['amount_paid']) &&
                    isset($_POST['time_date_of_payment']) && !empty($_POST['time_date_of_payment']) &&
                    isset($_POST['transaction_code']) && !empty($_POST['transaction_code']) &&
                    isset($_POST['msidn']) && !empty($_POST['msidn']) 
                    )
                    {  
                        $mode_of_payment= trim($_POST['mode_of_payment']);
                        $amount_paid=trim($_POST['amount_paid']);
                        $time_date_of_payment=trim($_POST['time_date_of_payment']);
                        $transaction_code=trim($_POST['transaction_code']);
                        $msidn=trim($_POST['msidn']);

                        
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorCreatePayment";

                            $myvars='session_key='.$_SESSION['session_key'].'&mode_of_payment='.$mode_of_payment.'&amount_paid='.$amount_paid.'&time_date_of_payment='.$time_date_of_payment.'&transaction_code='.$transaction_code.'&msidn='.$msidn.'&email_address='.$email_address.'&policy_id='.$policy_id;

                            $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['cookie'],'Origin:/senior_administrator/policy_view_payments_specific.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                               
                                    
                                  header('location: '.$action_page.'&message='.$message_is.'&type=1&amount_paid='.$amount_paid.'&transaction_code='.$transaction_code.'&mode_of_payment='.$mode_of_payment.' ');//
                            }
                            else//else failed
                            {

                                header('location: '.$action_page.'&message='.$message_is.'&type=2');//
                            } 
                       

                    }
                   
                
                    
               //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserPayments";

        $myvars='session_key='.$_SESSION['session_key'].'&email_address='.$email_address.'&policy_id='.$policy_id;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/policy_view_payments_specific.php');

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
                                                                                 <td id="edit_td'.$count.'" ><a href="'.$edit_page.'&_id='.$_id.'&mode_of_payment='.$mode_of_payment.'&amount_paid='.$amount_paid.'&time_date_of_payment='.$time_date_of_payment.'&transaction_code='.$transaction_code.'&msidn='.$msidn.'" title="Edit '.strtoupper($mode_of_payment).' '.strtoupper($transaction_code).' KES. '.number_format($amount_paid).'">Edit</a></td>
                                                                                 <td id="delete_td'.$count.'" ><span id="red_text_span"><a href="'.$delete_page.'&_id='.$_id.'&mode_of_payment='.$mode_of_payment.'&amount_paid='.$amount_paid.'&time_date_of_payment='.$time_date_of_payment.'&transaction_code='.$transaction_code.'&msidn='.$msidn.'" title="Remove '.strtoupper($mode_of_payment).' '.strtoupper($transaction_code).' KES. '.number_format($amount_paid).'">Delete</a></span></td>   
                                                                                     

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
                                             <th><a href="#" onmouseover="hover_link(\'edit_td\',\''.$count.'\');" onmouseout="out_link(\'edit_td\',\''.$count.'\');" >Edit</a></th>
                                                 <th><a href="#" onmouseover="hover_link(\'delete_td\',\''.$count.'\');" onmouseout="out_link(\'delete_td\',\''.$count.'\');" >Delete</a></th>
                                             
                                             </tr>';
                             
                             $table='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table.'
                                          </table>';
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
            // $message=$message_is;
           // header('location: logs_view.php?c='.$_GET['c'].'&l='.$_GET['l'].'&s='.$_GET['s'].'&message='.$message_is.'&type=2');//
            if($message_is=='')
            {
                header('location: ../logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
                $message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
        }
                
                
         
                    if(isset($_GET['transaction_code']) && !empty($_GET['transaction_code']) && 
                    isset($_GET['mode_of_payment']) && !empty($_GET['mode_of_payment']) &&
                    isset($_GET['amount_paid']) && !empty($_GET['amount_paid']) 
                    )
                    {
                            //login and send message
                            $message_is_is=login_behalf_of_client($email_address,'/senior_administrator/policy_activate_deactivate.php');

                            $message_send="Hello ".$full_names.", A payment of KES. ". number_format($_GET['amount_paid'])." of ref ".$_GET['transaction_code']." paid via ".strtoupper($_GET['mode_of_payment'])." on ".return_date_function( (time()*1000))." has been allocated. The balance is KES". number_format($balance_is['balance']).". Please click this link to view your payment details/pay balance.".$message_is_is;

                            //send message to notify on claim
                            send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$phone_number,'/senior_administrator/policy_view_payments_specific.php');
                            $header_email_is="New payment for policy ". strtoupper($company_name);
                            send_email_message($_SESSION['session_key'],$_SESSION['cookie'],$email_address,$header_email_is,$message_send,'/senior_administrator/policy_view_payments_specific.php'); 
                            
                              header('location: '.$action_page.'&message='.$_GET['message'].'&type='.$_GET['type'].' ');//
                    }
                
                
}               



?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <title>Policy Payment User Policy Type Specific</title>
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
									<h2><?php echo $policy_name;?></h2>
                                </div>
                               
                            </div>
                       
                        </div>
                        <div class="body">
		          <?php echo $message;?><br>
         <form action="<?php echo $action_page;?>" method="POST">
            <input type="text" name="mode_of_payment" placeholder="Mode of payment"/>
            <input type="number" name="amount_paid" placeholder="Amount paid"/>
            <input type="date" name="time_date_of_payment"  placeholder="Date"/>
            <input type="text" name="transaction_code"  placeholder="Transaction code"/>
             <input type="number"  name="msidn" placeholder="254711222333"/>
             <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
         </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# CPU Usage -->
			<!-- innerbody -->
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
		
		     <?php echo $table;?><br>
                            
                   
              
              <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
		 
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# --> <a href="../senior_administrator/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 
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