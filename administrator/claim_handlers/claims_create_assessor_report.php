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





if(isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re'])  && !empty($_GET['re']) && isset($_GET['e']) && !empty($_GET['e']) && isset($_GET['f']) && !empty($_GET['f'])  && !empty($_GET['_id']) && isset($_GET['_id']) && isset($_GET['c_n']) && !empty($_GET['c_n']) 
  && isset($_GET['a_e']) && !empty($_GET['a_e']) && isset($_GET['c_h_e']) && !empty($_GET['c_h_e'])  && isset($_GET['t_s']) && !empty($_GET['t_s'])  && isset($_GET['a_f_n']) && !empty($_GET['a_f_n'])   && isset($_GET['a_p_n']) && !empty($_GET['a_p_n']) && isset($_GET['c_h_f_n']) && !empty($_GET['c_h_f_n']) && isset($_GET['c_h_p_n']) && !empty($_GET['c_h_p_n'])       
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
       
        
        
         $full_link="claims_create_assessor_report.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&c_h_e=".$claim_handler_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&c_h_f_n=".$claim_handler_full_names.'&c_h_p_n='.$claim_handler_phone_number;//for form submission
         $return_link="claims_view_assessor_reports.php?l=".$limit."&s=".$skip."&re=".$rows_every."&e=".$email_address."&f=".$full_names."&_id=".$_id.'&c_n='.$claim_number."&a_e=".$assessor_email."&c_h_e=".$claim_handler_email."&t_s=".$time_stamp."&a_f_n=".$assessor_full_names."&a_p_n=".$assessor_phone_number."&c_h_f_n=".$claim_handler_full_names.'&c_h_p_n='.$claim_handler_phone_number;//for form submission
         
         //SUBMIT
        $header_array=array();
        $reference_array=array();
        $damage_array=array();
        $report_body=array();
        $report_estimated_cost=array();
        
        //header
        if(isset($_POST['header_fields']) && !empty($_POST['header_fields']) )
        {
            $header_fields=$_POST['header_fields'];
            
            //loop
            for ($index = 0; $index < count($header_fields); $index++) 
            {
                $header_field=trim($header_fields[$index]);
                $header_array[$index]=$header_field;
            }
        }
        
        //reference
        if(isset($_POST['reference_fields']) && !empty($_POST['reference_fields']) )
        {
            $reference_fields=$_POST['reference_fields'];
            
            //loop
            for ($index = 0; $index < count($reference_fields); $index++) 
            {
                $reference_field=trim($reference_fields[$index]);
                $reference_array[$index]=$reference_field;
            }
        }
        
        //body_fields
        if(isset($_POST['body_fields_1']) && !empty($_POST['body_fields_1']) && isset($_POST['body_fields_2']) && !empty($_POST['body_fields_2']) )
        {
            $body_fields_1=$_POST['body_fields_1'];
            $body_fields_2=$_POST['body_fields_2'];
            
            //loop
            for ($index = 0; $index < count($body_fields_1); $index++) 
            {
                $body_field_1=trim($body_fields_1[$index]);
                $body_field_2=trim($body_fields_2[$index]);
                
                $body_array=array($body_field_1=>$body_field_2);
                
                $report_body[$index]=$body_array;
            }
        }
        
        
         //damage_
        if(isset($_POST['damage_fields']) && !empty($_POST['damage_fields']) )
        {
            $damage_fields=$_POST['damage_fields'];
            
            //loop
            for ($index = 0; $index < count($damage_fields); $index++) 
            {
                $damage_field=trim($damage_fields[$index]);
                $damage_array[$index]=$damage_field;
            }
        }
        
        //estimated_cost_fields
        if(isset($_POST['estimated_cost_fields_1']) && !empty($_POST['estimated_cost_fields_1']) && isset($_POST['estimated_cost_fields_2']) && !empty($_POST['estimated_cost_fields_2']) )
        {
            $estimated_cost_fields_1=$_POST['estimated_cost_fields_1'];
            $estimated_cost_fields_2=$_POST['estimated_cost_fields_2'];
            
            //loop
            for ($index = 0; $index < count($estimated_cost_fields_1); $index++) 
            {
                $estimated_cost_field_1=trim($estimated_cost_fields_1[$index]);
                $estimated_cost_field_2=(int)trim($estimated_cost_fields_2[$index]);
                
                $estimated_array=array($estimated_cost_field_1=>$estimated_cost_field_2);
                
                $report_estimated_cost[$index]=$estimated_array;
            }
        }

        /*
echo json_encode($header_array);
echo '<hr>';
echo json_encode($reference_array);
echo '<hr>';
echo json_encode($report_body);
echo '<hr>';
echo json_encode($damage_array);
echo '<hr>';
echo json_encode($report_estimated_cost);
echo '<hr>';
*/

//submit
if(!empty($header_array) &&
    !empty($reference_array) &&
    !empty($report_body) &&
    !empty($damage_array) &&
    !empty($report_estimated_cost) 
)
{  
   

    
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AssessorCreateClaimReport";

        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id.'&report_header='.json_encode($header_array).'&report_reference='.json_encode($reference_array).'&report_body='.json_encode($report_body).'&report_damage='.json_encode($damage_array).'&report_estimated_cost='.json_encode($report_estimated_cost);

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/claim_handlers/claims_create_assessor_report.php');

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

        $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message

        if($check_is==true)//if check is true
        {
             //login and send message
                $message_is_is=login_behalf_of_client($email_address,'/repair_garage/claims_create_garage_report.php');
                //get client phone number
                $client_info=get_specific_client_details($_SESSION['session_key'],$_SESSION['cookie'],$email_address,'/claim_handlers/claims_create_assessor_report.php');
                $message_send="Hello ".$full_names.", claim number ".$claim_number." has a new report in your DENKIM account. Please login with the following link to view. ".$message_is_is;
                //send message to notify on claim
                send_sms_message($_SESSION['session_key'],$_SESSION['cookie'],$message_send,$client_info['phone_number'],'/claim_handlers/claims_create_assessor_report.php');

              header('location: '.$return_link.'&message='.$message_is.'&type=1');//
        }
        else//else failed
        {

            header('location: '.$return_link.'&message='.$message_is.'&type=2');//
        } 
    

} 
  
                  
       
}








    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title>Create report</title>
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
                <a class="navbar-brand" href="../assessor_loss_adjsuter/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Assessor/loss adjuster</div>
                    <div class="email">Administrator</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="personal_information.php" title="Edit your name, phone number and national ID"><i class="material-icons">account_box</i>View Profile</a></li>
                            <li role="seperator" class="divider"></li>
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
                       <a href="../assessor_loss_adjsuter/" title="Go to the main page">
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
                       <a href="messages.php" title="Send and get messages">
                            <i class="material-icons">message</i>
                            <span>Messages </span>
                        <?php echo get_inbox_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/assessor_loss_adjsuter/*');?></a>
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
                                    <h2><?php echo $full_names;?></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
						
		   <<h4>Claim number:<br><br> <?php echo $claim_number;?><br><br></h4> 
                   <h4>Claim handler: <br><br><?php echo $claim_handler_full_names."<br>".$claim_handler_email."<br>".$claim_handler_phone_number."<br><br>";?></h4> 
                     <h4>Assigned Assessor/Loss adjuster: <br><br><?php echo $assessor_full_names."<br>".$assessor_email."<br>".$assessor_phone_number."<br>";?></h4> 
                     
                     
         <?php echo $message;?><br>
         <form action="<?php echo $full_link;?>" method="POST">
              <h4>Header</h4>
              <custom_clicky_thingy onclick="header_fields();">Add headers</custom_clicky_thingy>
              <ol id="header_data"></ol>
              
              <h4>Reference</h4>
              <custom_clicky_thingy onclick="reference_fields();">Add references</custom_clicky_thingy>
              <ol id="reference_data"></ol>
              
              <h4>Body</h4>
              <custom_clicky_thingy onclick="body_fields();">Add body</custom_clicky_thingy>
              <ol id="body_data"></ol>
              
              <h4>Damage</h4>
              <custom_clicky_thingy onclick="damage_fields();">Add damage</custom_clicky_thingy>
              <ol id="damage_data"></ol>
              
              <h4>Estimated cost</h4>
              <custom_clicky_thingy onclick="estimated_cost_fields();" >Add estimated cost</custom_clicky_thingy>
              <ol id="estimated_cost_data"></ol>
              
              <h4>Estimated total: <input type="text" id="estimated_total" disabled="true" placeholder="0"/></h4>
              
              
            
            
             <button type="submit" class="btn btn-primary m-t-15 waves-effect">Save</button>
         </form>
           <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           
           <script type="text/javascript" src="../../javascript/assessor_report.js"></script>
          
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# -->
		 <a href="../assessor_loss_adjsuter/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 	 
            </div>
        </div>
		
		<script>
function printContent(el){
var restorepage = $('body').html();
var printcontent = $('.' + el).clone();
$('body').empty().html(printcontent);
window.print();
$('body').html(restorepage);
}
</script>
<button id="print" onclick="printContent('card');" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">print</i>Print</button>	
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