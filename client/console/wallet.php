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

if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )
{//if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        
        $action_page="wallet.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for form submission
        $full_link="wallet.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for form submission
        $link_without_sort_column_sort_order="wallet.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for headers sorting
        $link_without_limit_skip_rows_every="wallet.php";//for browsing
        
       
        
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
                            <th><a href="#" onmouseover="hover_link(\'particulars_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'particulars_td\',\''.$total_for_table_rows.'\');" >Particulars</a></th>
                            <th><a href="#" onmouseover="hover_link(\'transaction_code_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'transaction_code_td\',\''.$total_for_table_rows.'\');" >Transaction code</a></th>
                             <th><a href="#"onmouseover="hover_link(\'use_date_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'use_date_td\',\''.$total_for_table_rows.'\');" >Use date</a></th>
                           <th><a href="#"onmouseover="hover_link(\'time_date_of_payment_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'time_date_of_payment_td\',\''.$total_for_table_rows.'\');" >Date of payment</a></th>
                           
                            
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
                  
                  $seen_status_is=$seen_status==0? 'unseen' : 'seen'; 
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                                                         
                  $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                <td>'.($count+1).'</td>  
                                                                <td id="mode_of_payment_td'.$count.'" >'.$mode_of_payment.'</td>
                                                                <td id="camount_paid_td'.$count.'" >'.number_format($amount_paid).'</td>
                                                                <td id="particulars_td'.$count.'" >'.$particulars.'</td>  
                                                                <td id="transaction_code_td'.$count.'" >'.$transaction_code.'</td>
                                                                <td id="use_date_td'.$count.'" >'.return_simple_date_function(strtotime($use_date)*1000).'</td>
                                                                <td id="time_date_of_payment_td'.$count.'" >'.$time_date_of_payment.'</td>   
                                                                
                                                </tr>';
                  $table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header
                  
                  $count++;
                  $from_one_counter++;
            }//end of foreach $message_is as $value
            
            $table='<table>'.$table_head.$table.'
                         </table>';
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
            if($message_is=='')
            {
                header('location: ../logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
                $message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
           // header('location: logs_view.php?c='.$_GET['c'].'&l='.$_GET['l'].'&s='.$_GET['s'].'&message='.$message_is.'&type=2');//
        }
        
}////if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re'])  )


            


?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <title>Wallet</title>
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
                        <a href="wallet.php?l=10&s=0&re=10" title="Add money to wallet">
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
		     
                     
                     <?php
                     if(isset($_POST['total']) && !empty(isset($_POST['total'])) &&
                    isset($_POST['phone_number']) && !empty(isset($_POST['phone_number']))  &&
                   (  isset($_POST['use_date']) && !empty(isset($_POST['use_date'])) ||  isset($_GET['use_date']) && !empty(isset($_GET['use_date']))  )
                          )
                    {//if($account_number!=0)
                         
                         $use_date=isset($_POST['use_date'])?$_POST['use_date']:$_GET['use_date'];
                         ?>
                            <ol>
                                <h4>Lipa na MPESA</h4>
                                <li>Paybill: 906238</li>
                                <li>Account number: <?php echo $account_number?></li>
                                <li>Use date starts on: <?php
                                            //echo $account_number
                                            echo return_date_function( (strtotime($use_date)*1000));    
                                        ?></li>
                                <form action="<?php echo $action_page?>&account_number=<?php echo $account_number?>&use_date=<?php echo $use_date?>" method="POST">
                                    Amount<input type="number" value="<?php echo round($_POST['total'], 0, PHP_ROUND_HALF_UP);?>" name="total" min="1" max="70000"><br>
                                    Mobile number(2547XXXXXXX)<input type="number" value="<?php echo $_POST['phone_number'];?>" name="phone_number"><br>
                                    <input type="submit" value="Pay direct">
                                </form>
                                
                         
                            </ol>
                         <?php
                     }//if($account_number!=0)
                     else
                     {//if($account_number!=0)
                            ?>
                            <ol>
                                <h4>Lipa na MPESA</h4>
                                <form action="<?php echo $action_page?>" method="POST">
                                    Amount<input type="number" required name="total" min="1" max="70000"><br>
                                    Mobile number(2547XXXXXXX)<input required type="number" value="<?php echo $phone_number;?>" name="phone_number"><br>
                                    Use date starts on<br><input type="date" value="<?php echo return_date_actual_function( (1000* time()) )?>" name="use_date" required  min="<?php echo return_date_actual_function( (1000* time()) )?>" > <br><br>
                                    <input type="submit" value="Make payment">
                                </form>
                               
                         
                            </ol>
                         <?php
                     }//if($account_number!=0)
                     
                     
                     ?>
                     <hr>
                   <br>
                   <form action="<?php echo $action_page?>" method="POST"><input type="submit" value="Reload"></form>
                   
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