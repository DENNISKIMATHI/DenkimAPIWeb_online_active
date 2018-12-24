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



if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re'])  && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f'])  && !empty($_GET['_id']) && isset($_GET['_id']) && isset($_GET['c_n']) && !empty($_GET['c_n']) 
  && isset($_GET['a_e']) && !empty($_GET['a_e']) && isset($_GET['c_h_e']) && !empty($_GET['c_h_e'])  && isset($_GET['t_s']) && !empty($_GET['t_s'])  && isset($_GET['a_f_n']) && !empty($_GET['a_f_n'])   && isset($_GET['a_p_n']) && !empty($_GET['a_p_n']) && isset($_GET['c_h_f_n']) && !empty($_GET['c_h_f_n']) && isset($_GET['c_h_p_n']) && !empty($_GET['c_h_p_n'])       
   && isset($_GET['r_id']) && !empty($_GET['r_id']) 
          )
{
	                                                                                                                                        
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $rows_every=trim($_GET['re']);
        $email_address=trim($_GET['e']);
        $full_names=trim($_GET['f']);
        $_id=trim($_GET['_id']);
        $claim_number=trim($_GET['c_n']);
        
        $assessor_email=trim($_GET['a_e']);
        $claim_handler_email=trim($_GET['c_h_e']);
        $time_stamp=trim($_GET['t_s']);
        $assessor_full_names=trim($_GET['a_f_n']);
        $assessor_phone_number=trim($_GET['a_p_n']);
        $claim_handler_full_names=trim($_GET['c_h_f_n']);
        $claim_handler_phone_number=trim($_GET['c_h_p_n']);
       
        $report_id=trim($_GET['r_id']);
        
         $full_link="claims_view_assessor_reports_specific.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&c_h_e=".$claim_handler_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&c_h_f_n=".$claim_handler_full_names.'&c_h_p_n='.$claim_handler_phone_number."&r_id=".$report_id;//for form submission
         $return_link="claims_view_assessor_reports.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&c_h_e=".$claim_handler_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&c_h_f_n=".$claim_handler_full_names.'&c_h_p_n='.$claim_handler_phone_number."&r_id=".$_id_is;//for form submission
                                  
       //fetch reports
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchClaimReports";

        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;
        
        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/claims_view_assessor_reports_specific.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        //sanitize the json
        //$returned_json= str_replace('\\', '', $returned_json);
         
        $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
         
        if($check_is==true)//if check is true
        {
                $count=0;
                foreach ($message_is as $value) 
                {//start of foreach $message_is as $value
                        $_id_is=$value['_id']['$oid'];
                        
                        
                        if($_id_is==$report_id)
                        {//start of if if($_id_is==$report_id)
                            
                             
                            $claim_id_is=$value['claim_id'];      
                            $assessor_email_email_is=$value['assessor_email'];
                            
                            $report_header=$value['report_header'];
                            $report_reference=$value['report_reference'];
                            $report_body=$value['report_body'];
                            $report_damage=$value['report_damage'];
                            $report_estimated_cost=$value['report_estimated_cost'];
                            
                            $assessor_full_names_is=$value['assessor_full_names'];
                            $assessor_phone_numbe_isr=$value['assessor_phone_number'];
                            $time_stamp_is=$value['time_stamp'];
                            
                            
                            
                                            //loop header
                                            $count=0;
                                            foreach ($report_header as $key => $value) 
                                            {
                                                $row_color=$count%2;
                                                $row_color=$row_color==0?'odd':'even';
                                                          
                                                $table_header=$table_header.'<tr class="'.$row_color.'" id="row_data">
                                                                           
                                                                                    <td id="header_item_td'.$count.'" >'.$value.'</td>
                                                                            </tr>';
                                                $count++;
                                            }

                                                 $table_header_head='<tr bgcolor="white">
                                                                
                                                                <th><a href="#"onmouseover="hover_link(\'header_item_td\',\''.$count.'\');" onmouseout="out_link(\'header_item_td\',\''.$count.'\');" >Header</a></th>
                                                                </tr>';

                                                 $table_header='<table class="table table-bordered table-hover table-responsive">'.$table_header_head.$table_header.'</table>';
                                                 
                                            //loop reference
                                            $count=0;
                                            foreach ($report_reference as $key => $value) 
                                            {
                                                $row_color=$count%2;
                                                $row_color=$row_color==0?'odd':'even';
                                                          
                                                $table_reference=$table_reference.'<tr class="'.$row_color.'" id="row_data">
                                                                             
                                                                                    <td id="reference_item_td'.$count.'" >'.$value.'</td>
                                                                            </tr>';
                                                $count++;
                                            }

                                                 $table_reference_head='<tr bgcolor="white">
                                                               
                                                                 <th><a href="#"onmouseover="hover_link(\'reference_item_td\',\''.$count.'\');" onmouseout="out_link(\'reference_item_td\',\''.$count.'\');" >Reference</a></th>
                                                                </tr>';

                                                 $table_reference='<table class="table table-bordered table-hover table-responsive">'.$table_reference_head.$table_reference.'</table>';
                                                 
                                            //loop body
                                            $count=0;
                                            foreach ($report_body as $key => $value) 
                                            {
                                                $row_color=$count%2;
                                                $row_color=$row_color==0?'odd':'even';

                                                foreach ($value as $key_real => $value_real) 
                                                {
                                                    $table_body=$table_body.'<tr class="'.$row_color.'" id="row_data">
                                                                              
                                                                                <td id="body_lead_td'.$count.'" >'.$key_real.'</td>
                                                                                    <td id="body_item_td'.$count.'" >'.$value_real.'</td>
                                                                            </tr>';
                                                }

                                                $count++;
                                            }

                                                 $table_body_head='<tr bgcolor="white">
                                                                
                                                                <th><a href="#"onmouseover="hover_link(\'body_lead_td\',\''.$count.'\');" onmouseout="out_link(\'body_lead_td\',\''.$count.'\');" >Title</a></th>
                                                                <th><a href="#"onmouseover="hover_link(\'body_item_td\',\''.$count.'\');" onmouseout="out_link(\'body_item_td\',\''.$count.'\');" >Information</a></th>
                                                                </tr>';

                                                 $table_body='<table class="table table-bordered table-hover table-responsive">'.$table_body_head.$table_body.'</table>';
                                        
                                            //loop damage
                                            $count=0;
                                            foreach ($report_damage as $key => $value) 
                                            {
                                                $row_color=$count%2;
                                                $row_color=$row_color==0?'odd':'even';
                                                          
                                                $table_damage=$table_damage.'<tr class="'.$row_color.'" id="row_data">
                                                                             
                                                                                    <td id="damage_item_td'.$count.'" >'.$value.'</td>
                                                                            </tr>';
                                                $count++;
                                            }

                                                 $table_damage_head='<tr bgcolor="white">
                                                                
                                                                 <th><a href="#"onmouseover="hover_link(\'damage_item_td\',\''.$count.'\');" onmouseout="out_link(\'damage_item_td\',\''.$count.'\');" >Damage</a></th>
                                                                </tr>';

                                                 $table_damage='<table class="table table-bordered table-hover table-responsive">'.$table_damage_head.$table_damage.'</table>';
                                                 
                                                 
                                            //loop estimated_cost
                                            $count=0;
                                            $total=0;
                                            foreach ($report_estimated_cost as $key => $value) 
                                            {
                                                $row_color=$count%2;
                                                $row_color=$row_color==0?'odd':'even';

                                                foreach ($value as $key_real => $value_real) 
                                                {
                                                    $table_estimated_cost=$table_estimated_cost.'<tr class="'.$row_color.'" id="row_data">
                                                                             
                                                                                <td id="estimated_cost_lead_td'.$count.'" >'.$key_real.'</td>
                                                                                    <td id="estimated_cost_item_td'.$count.'" >'.number_format($value_real,2).'</td>
                                                                            </tr>';
                                                    
                                                    $total+=$value_real;
                                                }

                                                $count++;
                                            }

                                                 $table_estimated_cost_head='<tr bgcolor="white">
                                                                
                                                                <th><a href="#"onmouseover="hover_link(\'estimated_cost_lead_td\',\''.$count.'\');" onmouseout="out_link(\'estimated_cost_lead_td\',\''.$count.'\');" >Cost description</a></th>
                                                                <th><a href="#"onmouseover="hover_link(\'estimated_cost_item_td\',\''.$count.'\');" onmouseout="out_link(\'estimated_cost_item_td\',\''.$count.'\');" >Actual cost(KES)</a></th>
                                                                </tr>';
                                                 
                                                 $table_estimated_cost_footer='<tr bgcolor="white">
                                                                
                                                                <th>TOTAL</th>
                                                                <th>'.number_format($total,2).'</th>
                                                                </tr>';

                                                 $table_estimated_cost='<table class="table table-bordered table-hover table-responsive">'.$table_estimated_cost_head.$table_estimated_cost.$table_estimated_cost_footer.'</table>';
                        }//end of if if($_id_is==$report_id)

                }//end of foreach $message_is as $value

                

              //header('location: '.$return_link.'&message='.$message_is.'&type=1');//
        }
        else//else failed
        {

            //header('location: '.$return_link.'&message='.$message_is.'&type=2');//
        } 
  
                  
       
}


    
//check login
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <title>View report specific</title>
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
                             Assesor report
                                
                            </h2>
                          
                          
                        </div>
                        <div class="body">
                            <div class="row clearfix">
							
                                 <h4>Claim number: <?php echo $claim_number;?></h4> 
                                    <h4>Claim handler: <?php echo $claim_handler_full_names."[".$claim_handler_email."/".$claim_handler_phone_number."]";?></h4> 
                                      <h4>Assigned Assessor/Loss adjuster: <?php echo $assessor_full_names."[".$assessor_email."/".$assessor_phone_number."]";?></h4> 

                          <h5>Header</h5>             
                          <?php echo $table_header;?><br>
                          <h5>Reference</h5>             
                          <?php echo $table_reference;?><br>
                          <h5>Body</h5>             
                          <?php echo $table_body;?><br>
                          <h5>Damage</h5>             
                          <?php echo $table_damage;?><br>
                          <h5>Estimated cost</h5>             
                          <?php echo $table_estimated_cost;?><br>


                            <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>

                            <script type="text/javascript" src="../../javascript/highlight.js"></script>
						   
						   
						   
						   
						   
						   
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
