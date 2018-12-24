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

$source='/client/console/messages.php';

//get personal email for email to exclude in list
$personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],$source);
//echo json_encode($personal_details_array);
//function make message list box
$drop_down_list=make_message_drop_down_box($personal_details_array['email_address'],$_SESSION['session_key'],$_SESSION['cookie'],$source,false);//is admin is true

$name=$_FILES['file']['name'];//file name
$type=$_FILES['file']['type'];//type of file
$temp=$_FILES['file']['tmp_name'];//temprorary location of files when uploaded

$file_url='';


 //submit
                    if(isset($_POST['messages_recipient_list']) && !empty($_POST['messages_recipient_list']) && 
                    isset($_POST['header']) && !empty($_POST['header']) 
                    )
                    {  
                                
                                //upload 
                                if(!empty($name))
                                {
                                    //make dir
                                    $time_is=  time();
                                    $dir_is='../../temp_uploads/';
                                    mkdir($dir_is.$time_is.'/');//making the directory
                                    $location=$dir_is.$time_is.'/';
                                    move_uploaded_file($temp, $location.$name);

                                    $file_name_with_full_path = realpath($location.$name);

                                    //curl
                                     $cfile = new CURLFile($file_name_with_full_path,$type,$name);

                                    //send
                                    $url_is=the_api_authentication_files_url_is()."denkimAPIFiles/MainPackages.UploadFiles?session_key=".$_SESSION['session_key'];

                                    $myvars=array('file'=> $cfile );

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$source);

                                    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                                    $returned_json_decoded= json_decode($returned_json,true);//decode

                                    $check_is=$returned_json_decoded["check"];//check

                                    $message_is=$returned_json_decoded["message"];//message

                                    $file=$returned_json_decoded["file"];//message

                                    if($check_is==true)//if check is true
                                    {
                                            //remove file
                                            unlink($location.$name);
                                            rmdir($location);  
                                            $file_url= $file;
                                           // header('location: upload_html_logo.php?message='.$message_is.': '.$file.'&type=1');//
                                    }

                                }
                                
                                $messages_recipient_list=trim($_POST['messages_recipient_list']);
                                $unique_separator=  return_unique_separator_email_name();
                                $explode_mail_name=  explode('_'.$unique_separator.'_', $messages_recipient_list);
                                $email_address_is=$explode_mail_name[0];
                                $full_names_is=$explode_mail_name[1];
                                
                                $header=trim($_POST['header']);

                                $content=trim($_POST['content']);
                                
                            
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.SendMessage";

                            $myvars='session_key='.$_SESSION['session_key'].'&header='.$header.'&content='.$content.'&attachment='.$file_url.'&to='.$email_address_is;

                            $header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$_SESSION['cookie']);

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                  $view_link='messages_view.php?re='.$email_address_is.'&fn='.$full_names_is.'&l=10&s=0';  
                                  header('location: '.$view_link.'&message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: messages.php?message='.$message_is.'&type=2');//
                            } 
                       
                    }
                   
//fetch messages
$url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchMessages";

$myvars='session_key='.$_SESSION['session_key'];

$header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$_SESSION['cookie']);

$returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

$returned_json_decoded= json_decode($returned_json,true);//decode

$check_is=$returned_json_decoded["check"];//check

$message_is=$returned_json_decoded["message"];//message

if($check_is==true)//if check is true
{

                                foreach ($message_is as $value) 
                                {//start of foreach $message_is as $value
                                    
                                    $header=$value['header'];
                                    $content=$value['content'];
                                    $time_stamp=$value['time_stamp'];
                                    $recipient_email=$value['recipient_email'];
                                    $recipient_name=$value['recipient_name'];
                                    $total_messages=$value['total_messages'];
                                    $unread_messages=$value['unread_messages'];
                                    
                                    
                                    $content_is=$content==null? 'No message': $content;
                                     
                                   $row_color=$count%2;
                                   $row_color=$row_color==0?'odd':'even';
                                   
                                  
                                   $view_link='messages_view.php?re='.$recipient_email.'&fn='.$recipient_name.'&l=10&s=0&um='.$unread_messages;
                                   
                                   $table=$table.' <tbody role="rowgroup"> <tr  role="row" class="'.$row_color.'" id="row_data">
                                                                  
                                                                                 <td role="cell" id="recipient_name_td'.$count.'" >'.strtoupper($recipient_name).'</td>   
 
                                                                                 <td role="cell" id="total_td'.$count.'" >'.$unread_messages.'/'.$total_messages.'</td> 
                                                                                 <td role="cell" id="view_td'.$count.'" ><a href="'.$view_link.'" title="View all messages between '.strtoupper($recipient_name).' and you"  class="btn btn-primary btn-block" style="text-align:center;"><i class="fa fa-eye"> </i> Click to View </a></td>
                                                                                      

                                                                     </tr> </tbody>';
                                   
                                   $total_payments+=$amount_paid;//add to total payments
                                   $count++;
                                   
                             }//end of foreach $message_is as $value
                             
                             
                             
                             $table_head='<thead role="rowgroup"><tr role="row">
                                          
                                              <th role="columnheader"><a href="#"onmouseover="hover_link(\'recipient_name_td\',\''.$count.'\');" onmouseout="out_link(\'recipient_name_td\',\''.$count.'\');" >Recipient</a></th>
                   
                                             <th role="columnheader"><a href="#" onmouseover="hover_link(\'total_td\',\''.$count.'\');" onmouseout="out_link(\'total_td\',\''.$count.'\');" >Unread/Total</a></th>
                                             <th role="columnheader"><a href="#" onmouseover="hover_link(\'view_td\',\''.$count.'\');" onmouseout="out_link(\'view_td\',\''.$count.'\');" >View</a></th>
                                                
                                             </tr> </thead>';
                             
                             $table='<table role="table">'.$table_head.$table.'
                                          </table>';
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

                    
           
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <title>Messages</title>
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

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="css/themes/all-themes.css" rel="stylesheet" />
	
	      <link rel="stylesheet" href="tables/message.css">
	
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
		   <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                     <div class="panel-group" id="accordion_3" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingOne_3">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion_3" href="#collapseOne_3" aria-expanded="true" aria-controls="collapseOne_3">
                                                         View Recieved messages 
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne_3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_3">
                                                <div class="panel-body">
                                                <?php echo $table;?><br>
                           <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
		    
												   
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-warning">
                                            <div class="panel-heading" role="tab" id="headingTwo_3">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_3" href="#collapseTwo_3" aria-expanded="false"
                                                       aria-controls="collapseTwo_3">
                                                        Compose a Message 
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo_3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_3">
                                                <div class="panel-body">
                                                  
												  						
														    <?php echo $message;?><br><br>
                <form action="" method="POST" enctype="multipart/form-data" >
                    <?php echo $drop_down_list;?>
                  <input type="text" class="form-control" name="header" placeholder="subject"/><br>
                  <textarea name="content"  class="form-control" rows="12" placeholder="Message..." /></textarea>
				  <br><br>
                <input type="file" name="file"  class="form-control" title="Add attachment"/> 
				
                  
				   <button type="submit" class="btn btn-primary m-t-15 waves-effect">Send</button>
				   <br>
              </form>
							  
												  
                                                </div>
                                            </div>
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
