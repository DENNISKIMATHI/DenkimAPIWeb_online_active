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

//submit
if(isset($_POST['company_name']) && !empty($_POST['company_name']) && 
isset($_POST['fire_multiplier']) && !empty($_POST['fire_multiplier']) &&
isset($_POST['fire_html_url']) && !empty($_POST['fire_html_url']) &&
isset($_POST['burglary_multiplier']) && !empty($_POST['burglary_multiplier']) &&
isset($_POST['burglary_html_url']) && !empty($_POST['burglary_html_url']) &&
isset($_POST['all_risk_multiplier']) && !empty($_POST['all_risk_multiplier']) &&
isset($_POST['all_risk_html_url']) && !empty($_POST['all_risk_html_url']) &&
isset($_POST['policy_number']) && !empty($_POST['policy_number']) &&
isset($_POST['expiry_duration_days']) && !empty($_POST['expiry_duration_days']) &&
isset($_POST['logo_url']) && !empty($_POST['logo_url']) 
)
{  
    $company_name=trim($_POST['company_name']);
    $fire_multiplier=trim($_POST['fire_multiplier']);
    $fire_html_url=trim($_POST['fire_html_url']);
    $burglary_multiplier=trim($_POST['burglary_multiplier']);
    $burglary_html_url=trim($_POST['burglary_html_url']);
    $all_risk_multiplier=trim($_POST['all_risk_multiplier']);
    $all_risk_html_url=trim($_POST['all_risk_html_url']);
    $policy_number=trim($_POST['policy_number']);
    $expiry_duration_days=trim($_POST['expiry_duration_days']);
    $logo_url=trim($_POST['logo_url']);
    
    
            
    
            
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyCreateFireBurglaryTheftInsurance";

        $myvars='session_key='.$_SESSION['session_key'].'&company_name='.$company_name.'&fire_multiplier='.$fire_multiplier.'&fire_html_url='.$fire_html_url.'&burglary_multiplier='.$burglary_multiplier.'&burglary_html_url='.$burglary_html_url.'&all_risk_multiplier='.$all_risk_multiplier.'&all_risk_html_url='.$all_risk_html_url.'&policy_number='.$policy_number.'&expiry_duration_days='.$expiry_duration_days.'&logo_url='.$logo_url;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/junior_administrator/fire_burglary_theft_insurance.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

        $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message

        if($check_is==true)//if check is true
        {

              header('location: fire_burglary_theft_insurance.php?message='.$message_is.'&type=1');//
        }
        else//else failed
        {

            header('location: fire_burglary_theft_insurance.php?message='.$message_is.'&type=2');//
        } 
    

} 


//fetch
$returned_json_decoded= fetch_policy_type(6,'/junior_administrator/fire_burglary_theft_insurance.php');

$check_is=$returned_json_decoded["check"];//check

$message_is=$returned_json_decoded["message"];//message


if($check_is==true)//if check is true
{

               
                     $skip=0;
                     $count=$skip;//make count skipped rows

                      
                     
                      foreach ($message_is as $value) 
                      {//start of foreach $message_is as $value
                       
    
                            $company_name=$value['company_name'];
                            $fire_multiplier=$value['fire_multiplier'];
                            $fire_html_url=$value['fire_html_url'];
                            $burglary_multiplier=$value['burglary_multiplier'];
                            $burglary_html_url=$value['burglary_html_url'];
                            $all_risk_multiplier=$value['all_risk_multiplier'];
                            $all_risk_html_url=$value['all_risk_html_url'];
                            $policy_number=$value['policy_number'];
                            $expiry_duration_days=$value['expiry_duration_days'];
                            $logo_url=$value['logo_url'];
                            $time_stamp=$value['time_stamp'];


                            $row_color=$count%2;
                            $row_color=$row_color==0?'odd':'even';

                            $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                          <td>'.($count+1).'</td>  
                                                                          <td id="company_name_td'.$count.'" >'.$company_name.'</td>
                                                                          <td id="fire_multiplier_td'.$count.'" >'.$fire_multiplier.'</td>
                                                                          <td id="fire_html_url_td'.$count.'" ><a href="'.$fire_html_url.'" target="_blank">HTML</a></td>
                                                                          <td id="burglary_multiplier_td'.$count.'" >'.$burglary_multiplier.'</td>
                                                                          <td id="burglary_html_url_td'.$count.'" ><a href="'.$burglary_html_url.'" target="_blank">HTML</a></td>
                                                                          <td id="all_risk_multiplier_td'.$count.'" >'.$all_risk_multiplier.'</td>
                                                                          <td id="all_risk_html_url_td'.$count.'" ><a href="'.$all_risk_html_url.'" target="_blank">HTML</a></td>
                                                                              
                                                                          <td id="policy_number_td'.$count.'" >'.$policy_number.'</td>
                                                                              <td id="expiry_duration_days_td'.$count.'" >'.$expiry_duration_days.'</td>
                                                                          <td id="logo_url_td'.$count.'" ><a href="'.$logo_url.'" target="_blank">Logo</a></td>
                                                                          <td id="time_stamp_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                          <td id="delete_td'.$count.'" ><span id="red_text_span"><a href="policy_delete_type_specific.php?p='.$policy_number.'&t=6&s=fire_burglary_theft_insurance.php" title="Remove '.$policy_number.'">Delete</a></span></td>   
                                                        </tr>';
                            
                            $count++;
                           
                      }//end of foreach $message_is as $value
                      
                      
                      $table_head='<tr bgcolor="white">
                                   <th>#</th>
                                       <th><a href="#"onmouseover="hover_link(\'company_name_td\',\''.$count.'\');" onmouseout="out_link(\'company_name_td\',\''.$count.'\');" >Company name</a></th>
                                      <th><a href="#"onmouseover="hover_link(\'fire_multiplier_td\',\''.$count.'\');" onmouseout="out_link(\'fire_multiplier_td\',\''.$count.'\');" >Fire multiplier</a></th>
                                       <th><a href="#" onmouseover="hover_link(\'fire_html_url_td\',\''.$count.'\');" onmouseout="out_link(\'fire_html_url_td\',\''.$count.'\');" >Fire HTML</a></th>
                                      <th><a href="#" onmouseover="hover_link(\'burglary_multiplier_td\',\''.$count.'\');" onmouseout="out_link(\'burglary_multiplier_td\',\''.$count.'\');" >Burglary multiplier</a></th>
                                      <th><a href="#" onmouseover="hover_link(\'burglary_html_url_td\',\''.$count.'\');" onmouseout="out_link(\'burglary_html_url_td\',\''.$count.'\');" >Burglary HTML</a></th>
                                      <th><a href="#" onmouseover="hover_link(\'all_risk_multiplier_td\',\''.$count.'\');" onmouseout="out_link(\'all_risk_multiplier_td\',\''.$count.'\');" >All risk multiplier</a></th>
                                      <th><a href="#" onmouseover="hover_link(\'all_risk_html_url_td\',\''.$count.'\');" onmouseout="out_link(\'all_risk_html_url_td\',\''.$count.'\');" >All risk HTMK</a></th>
                                        
                                        <th><a href="#" onmouseover="hover_link(\'policy_number_td\',\''.$count.'\');" onmouseout="out_link(\'policy_number_td\',\''.$count.'\');" >Policy number</a></th>
                                       <th><a href="#" onmouseover="hover_link(\'expiry_duration_days_td\',\''.$count.'\');" onmouseout="out_link(\'expiry_duration_days_td\',\''.$count.'\');"> Expiry duration days</a></th>
                                       
                                        <th><a href="#" onmouseover="hover_link(\'logo_url_td\',\''.$count.'\');" onmouseout="out_link(\'logo_url_td\',\''.$count.'\');" >Logo</a></th>
                                       
                                        <th><a href="#" onmouseover="hover_link(\'time_stamp_td\',\''.$count.'\');" onmouseout="out_link(\'time_stamp_td\',\''.$count.'\');" >Date added</a></th>
                                      <th><a href="#" onmouseover="hover_link(\'delete_td\',\''.$count.'\');" onmouseout="out_link(\'delete_td\',\''.$count.'\');" >Delete</a></th>
                                      </tr>';
                      $table='<table class="table table-bordered table-hover table-responsive">'.$table_head.$table.'
                                   </table>';
}
else//else failed
{

   // header('location: fire_burglary_theft_insurance.php?message='.$message_is.'&type=2');//
            if($message_is=='')
            {
                header('location: ../logout.php?message=Your session has expired, please log in again!&type=2');
            }
            else
            {
                $message='<span id="bad_upload_message">'.$message_is.'</span>';
            }
} 


?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <title>Fire burglary theft insurance</title>
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
                <a class="navbar-brand" href="../junior_administrator/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Junior</div>
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
                       <a href="../junior_administrator/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="clients.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete clients, view their policies, select, delete and edit policies for them, create and delete claims, also make, veiw, edit and delete payments">
                            <i class="material-icons">contacts</i>
                            <span>Clients</span>
                        </a>
                    </li>
					 <li>
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
					<li>
					<a href="upload_html_logo.php" title="Upload logo and html for your policies to the file server">
                           <i class="material-icons">attachment</i>
                            <span>File Server</span>
                        </a>
                    </li> 
                    
                     
                    
                    <li>
                        <a href="tasks.php?l=10&s=0" title="Add and delete tasks">
                            <i class="material-icons">alarm</i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="clients_information.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add and share clients information">
                            <i class="material-icons">share</i>
                            <span>Clients information</span>
                        </a>
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
		       <?php echo $message;?><br>
                <form action="" method="POST">
            <input type="text" name="company_name" placeholder="Company name"/>
            <input type="text" name="fire_multiplier" placeholder="Fire multiplier"/>
            <input type="text" name="fire_html_url"  placeholder="Fire HTML url"/>
            <input type="text" name="burglary_multiplier"  placeholder="Burglary multiplier"/>
            <input type="text" name="burglary_html_url"  placeholder="Burglary HTML url"/>
             <input type="text" name="all_risk_multiplier"  placeholder="All risk multiplier"/>
            <input type="text" name="all_risk_html_url"  placeholder="All risk HTML url"/>
           
            <input type="text" name="policy_number"  placeholder="Policy number"/>
            <input type="number" name="expiry_duration_days"  placeholder="Expiry duration days"/>
             <input type="text" name="logo_url"  placeholder="Logo link"/>
            
             <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
         </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
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
            <!-- #END# -->
 <a href="../junior_administrator/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 			  
			  
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