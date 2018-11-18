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




if(isset($_GET['pn']) && !empty($_GET['pn']) )
{   
        $type_is=16;//type
         //fetch email
        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/select_policy_user_wiba_and_employers_insurance.php');
        $email_address=$personal_details_array['email_address'];
        
        $policy_number=trim($_GET['pn']);
        
        $action_page='select_policy_user_wiba_and_employers_insurance.php?pn='.$policy_number;
	 //fetch
        $returned_json_decoded= fetch_policy_type_specific($type_is,$policy_number,'/client/console/select_policy_user_wiba_and_employers_insurance.php');

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        if($check_is==true)//if check is true
        {
            $company_name=$message_is['company_name'];
            $wiba_categories=$message_is['wiba_categories'];
            $employee_liability_options=$message_is['employee_liability_options'];
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
                        header('location: ../mobile_logout.php?message=Your session has expired, please log in again!&type=2');
                    }
                    else
                    {
                         $message='<span id="bad_upload_message">'.$message_is.'</span>';
                    }
        }
        
        
        
        
        //submit
        if(isset($_POST['selected_employee_categories']) && !empty($_POST['selected_employee_categories'])      
                )
        {
            $selected_employee_categories=$_POST['selected_employee_categories'];
            $elected_empoloyee_liability_option=trim($_POST['elected_empoloyee_liability_option']);
            
            $selected_employee_categories_final=array();
            $counter=0;
            
            foreach ($selected_employee_categories as $cat_name=>$value) 
            {
                if(count($value)>0)
                {
                    foreach ($value as $value_inner) 
                    {
                        if($value_inner['employee_id']!='' && $value_inner['monthly_salary']!='')
                        {
                            $selected_employee_categories_final[$counter]['employee_id']=$value_inner['employee_id'];
                            $selected_employee_categories_final[$counter]['monthly_salary']=(int)$value_inner['monthly_salary'];
                            $selected_employee_categories_final[$counter]['category_name']=$cat_name;
                            $counter++;
                        }
                        
                    }
                     
                }
               
            }
           // echo json_encode($selected_employee_categories_final).'<hr>';
            //echo $elected_empoloyee_liability_option;
           
            $how_many=count($_SESSION['shoping_cart'][$type_is]);//for motor
            
            //get id
            $item_id=make_cart_item_id($type_is,$how_many);
            //assign
            $items_array_is=array('policy_number'=>$policy_number,
                
                                'selected_employee_categories'=>$selected_employee_categories_final,
                                'elected_empoloyee_liability_option'=>$elected_empoloyee_liability_option,
                
                                'employee_liability_options'=>$employee_liability_options,
                                'wiba_categories'=>$wiba_categories,
                
                                'company_name'=>$company_name,
                                'policy_number'=>$policy_number,
                                'expiry_duration_days'=>$expiry_duration_days,
                                'logo_url'=>$logo_url,
                                'html_url'=>$html_url,
                                'time_stamp'=>$time_stamp
                                );
            
             $array_kart=array();
            $array_kart[$type_is][0]=array($item_id=>$items_array_is);
            
            

             check_out_kart_with_email($array_kart,$email_address,'/client/console/select_policy_user_wiba_and_employers_insurance.php',$personal_details_array['full_names']);
            
           
            header('location: view_user_wiba_and_employers_insurance.php?message=Policy selected&type=1');//
             
        }
}


   
           
//echo json_encode($_SESSION['shoping_cart']);

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title>Wiba and employers liability insurance select</title>
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
                        <script src="../../javascript/jquery-1.11.1.min.js"></script>
                        <script src="../../javascript/combined_totals.js"></script>
                        <script type="text/javascript">
                            send_combined_and_get_aggregate_totals();
                            
                            </script>
                            
                                    <table style="font-size: 9px" >
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
               
               <div id="some_div_name_never_do_this">
                </div>
           
            <h2>Enter employee ID.</h2>
            <input type="text" id="employee_id_for_use_is"/><br>  
             <h2>Enter employee salary.</h2>
            <input type="number" id="employee_salary_for_use_is"/><br> 
            
            <select  name="list_of_categories" id="list_of_categories" onchange="add_employees_for_wiba_plan_b()">
                <option  value="">Select job category</option>
            <?php
                    foreach ($wiba_categories as $value) 
                    {
                        $id_for_use=md5($value['category_name'].$value['category_multiplier']);
                        $div_id='div_is_'.$id_for_use;
                        
                        ?>
                        <!--
                        <h3><?php echo $value['category_name'];?></h3>
                        <div id="<?php echo $div_id;?>">
                        </div>
                        <input type="button" value="Add employee" onclick="add_employees_for_wiba('<?php echo $id_for_use;?>','<?php echo $div_id;?>','<?php echo $value['category_multiplier'];?>','<?php echo $value['category_name'];?>')"/><br>
                        -->
                        <option  value="<?php echo $value['category_name'];?>" id_for_use="<?php echo $id_for_use;?>" category_multiplier="<?php echo $value['category_multiplier'];?>" category_name="<?php echo $value['category_name'];?>"   ><?php echo $value['category_name'];?></option>
                        <?php
                    }
            ?>
                        <br><input type="button" id="ädd_employee_btn" value="Add employee" onclick=""/><br>
            </select>
           <h2>Select employers liability insurance from the options below;</h2>
             <?php
                    foreach ($employee_liability_options as $value) 
                    {
                        $id_for_use=md5($value['option_name'].$value['limit'].$value['multiplier']);
                        $radio_is='radio_is_'.$id_for_use;
                        
                        ?>
                         
                         <label class="container"><?php echo $value['option_name'];?> <input type="radio" name="elected_empoloyee_liability_option" value="<?php echo $value['option_name'];?>" id="<?php echo $radio_is?>"  onclick="record_option('<?php echo $value['multiplier'];?>','<?php echo $radio_is?>');" /><span class="checkmark"></span></label>
                       
                        
                        <?php echo send_curl_post($value['html'], null, null);?>
                        <?php
                    }
            ?>
                        
            
            <b>Premium</b><br>
            <br>
            <span id="premium_span"></span>
                
                <h2>See What's Covered</h2>
                <?php echo $html_content;?>
                
 
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect" >Proceed and buy</button>
        </form>
        <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="../../javascript/wiba_and_employers_insurance_select.js"></script>
            
                
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