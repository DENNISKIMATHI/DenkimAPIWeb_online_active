<?php
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




if(isset($_GET['pn']) && !empty($_GET['pn']) )
{   
        $type_is=15;//type
         //fetch email
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/select_policy_user_motor_psv_uber_insurance.php');
        $email_address=$personal_details_array['email_address'];
        
        $policy_number=trim($_GET['pn']);
        
        $action_page='select_policy_user_motor_psv_uber_insurance.php?pn='.$policy_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/client/console/select_policy_user_motor_psv_uber_insurance.php');

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        if($check_is==true)//if check is true
        {
            $company_name=$message_is['company_name'];
            
            $v_percentage=$message_is['v_percentage'];
            $n_percentage=$message_is['n_percentage'];
            $minimum_excess_protector=$message_is['minimum_excess_protector'];
            $minimum_political_violence=$message_is['minimum_political_violence'];
            $excess_protector_multiplier=$message_is['excess_protector_multiplier'];
            $political_violence_multiplier=$message_is['political_violence_multiplier'];
            $aa_constant=$message_is['aa_constant'];
            
            $policy_number=$message_is['policy_number'];
            $expiry_duration_days=$message_is['expiry_duration_days'];
            $logo_url=$message_is['logo_url'];
            $html_url=$message_is['html_url'];
            $time_stamp=$message_is['time_stamp'];
            
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
        
        
        
        
         //submit
        if(isset($_POST['vehicle_value']) && !empty($_POST['vehicle_value']) &&
           isset($_POST['vehicle_registration_details']) && !empty($_POST['vehicle_registration_details']) &&
                isset($_POST['number_of_passengers']) && !empty($_POST['number_of_passengers'])
                )
        {
            $vehicle_value=trim($_POST['vehicle_value']);
            $vehicle_registration_details=trim($_POST['vehicle_registration_details']);
            $number_of_passengers=trim($_POST['number_of_passengers']);
            $excess_protector_percentage=trim($_POST['excess_protector_percentage']);
            $political_risk_terrorism_percentage=trim($_POST['political_risk_terrorism_percentage']);
            $aa_membership=trim($_POST['aa_membership']);
            
            $excess_protector_percentage_boolean=$excess_protector_percentage==1? 'true': 'false';
            $political_risk_terrorism_percentage_boolean=$political_risk_terrorism_percentage==1? 'true': 'false';
            $aa_membership_boolean=$aa_membership==1? 'true': 'false';
            //echo $insured_item_value.'--'.$excess_protector_percentage_is_boolean.'--'.$political_risk_terrorism_percentage_is_boolean.'--'.$aa_membership_is_boolean.'<hr>';
            
            $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
            //get id
            $item_id=make_cart_item_id($type_is,$how_many);
            //assign
            $items_array_is=array('policy_number'=>$policy_number,
                
                                'vehicle_value'=>(int)$vehicle_value,
                                'vehicle_registration_details'=>$vehicle_registration_details,
                                'number_of_passengers'=>(int)$number_of_passengers,
                                'excess_protector_percentage'=>$excess_protector_percentage_boolean,
                                'political_risk_terrorism_percentage'=>$political_risk_terrorism_percentage_boolean,
                                'aa_membership'=>$aa_membership_boolean,
                
                                'company_name'=>$company_name,
                
                                'v_percentage'=>$v_percentage,
                                'n_percentage'=>$n_percentage,
                                'minimum_excess_protector'=>$minimum_excess_protector,
                                'minimum_political_violence'=>$minimum_political_violence,
                                'excess_protector_multiplier'=>$excess_protector_multiplier,
                                'political_violence_multiplier'=>$political_violence_multiplier,
                                'aa_constant'=>$aa_constant,
                                
                                
                                'expiry_duration_days'=>$expiry_duration_days,
                                'logo_url'=>$logo_url,
                                'html_url'=>$html_url,
                                'time_stamp'=>$time_stamp
                                );
            
           $array_kart=array();
            $array_kart[$type_is][0]=array($item_id=>$items_array_is);
            
          


             check_out_kart_with_email($array_kart,$email_address,'/client/console/select_policy_user_motor_psv_uber_insurance.php',$personal_details_array['full_names']);
            
           
            header('location: view_user_motor_psv_uber_insurance.php?message=Policy selected&type=1');//
        }
        
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Motor psv, uber, taxify, little cab and institutional buses and vans insurance select</title>
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

   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
                                    
									<h2><?php echo $company_name;?></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
			        <img src="<?php echo $logo_url;?>"/>
        <?php echo $message;?><br>
         <form action="<?php echo $action_page;?>" method="post">
            <h2>Enter the value of the vehicle.</h2>
            <input type="number" min="1" name="vehicle_value" required v_percentage="<?php echo $v_percentage;?>" n_percentage="<?php echo $n_percentage;?>"  id="vehicle_value" placeholder="Vehicle value"/>
            <br>
            
            <h2>Enter the number of passengers.</h2>
            <input type="number" min="1" name="number_of_passengers"  id="number_of_passengers"  required v_percentage="<?php echo $v_percentage;?>" n_percentage="<?php echo $n_percentage;?>" placeholder="Number of passengers"/>
            <br>
            
            <h2>Enter the vehicle registration details.</h2>
            <textarea name="vehicle_registration_details" required placeholder="Vehicle registration details"></textarea>
            <br>
            
            
            
            <b>Premium</b><br>
            <br>
            <span id="premium_span"></span>
                
                <h2>See What's Covered</h2>
                <?php echo $html_content;?>
                <h2>Additional benefits</h2><br><br>
                    <div class="switch">
                Excess protector: <label><input type="checkbox" minimum_excess_protector="<?php echo $minimum_excess_protector;?>" excess_protector_multiplier="<?php echo $excess_protector_multiplier;?>" status_is="unchecked" name="excess_protector_percentage" id="excess_protector_percentage" value="1"><span class="lever"></span></label><span id="excess_protector_percentage_is_span"></span><br><br>
                Political Risk/terrorism: <label><input type="checkbox" minimum_political_violence="<?php echo $minimum_political_violence;?>" political_violence_multiplier="<?php echo $political_violence_multiplier;?>" status_is="unchecked"  name="political_risk_terrorism_percentage" id="political_risk_terrorism_percentage" value="1" ><span class="lever"></span></label> <span id="political_risk_terrorism_percentage_is_span"></span><br><br>
                AA Membership/Rescue: <label><input type="checkbox" name="aa_membership" id="aa_membership" value="1"  aa_constant="<?php echo $aa_constant;?>"  ><span class="lever"></span></label><span id="premium_span"><?php echo 'KES. '.number_format($aa_constant) ?></span><br><br>
  </div>
                <button type="submit" class="btn btn-primary m-t-15 waves-effect">Proceed and buy</button>
        </form>
        <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../../javascript/motor_psv_uber_insurance_select.js"></script>
            
                
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