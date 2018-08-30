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










if( isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) && isset($_GET['re']) && !empty($_GET['re']) && isset($_GET['fn']) && !empty($_GET['fn']))
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $recipient_email=trim($_GET['re']);
        $recipient_name=trim($_GET['fn']);
        
        $source='/client/console/messages_view.php';
      
        
        $full_link="messages_view.php?l=".$limit."&s=".$skip."&re=".$recipient_email."&fn=".$recipient_name;//for form submission
        $link_without_sort_column_sort_order="messages_view.php?l=".$limit."&s=".$skip."&re=".$recipient_email."&fn=".$recipient_name;//for headers sorting
        $link_without_limit_skip_rows_every="messages_view.php?l=".$limit."&s=".$skip."&re=".$recipient_email."&fn=".$recipient_name;//for browsing
        
        $return_link='messages.php';
       
        
      
        
        //form submission
        if(isset($_POST['limit_is']) && !empty($_POST['limit_is']) && 
                is_numeric($_POST['limit_is']) && 
                ( $_POST['skip_is']==0 || is_numeric($_POST['skip_is']) ))
        {
            $new_limit=trim($_POST['limit_is']);
            $new_skip=trim($_POST['skip_is']);
             header('location: '.$link_without_limit_skip_rows_every.'&l='.$new_limit.'&s='.$new_skip.'');//redirect back to form correctly
        }
        
        //make messages read if any
        if( isset($_GET['um']) && is_numeric($_GET['um']) && $_GET['um']>0)
        {
            
                $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchMessagesThreadMakeSeen";
                
                $header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$_SESSION['cookie']);

                $myvars='session_key='.$_SESSION['session_key'].'&recipient_email='.$recipient_email;

                $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                $returned_json_decoded= json_decode($returned_json,true);//decode

                $check_is=$returned_json_decoded["check"];//check

                $message_is=$returned_json_decoded["message"];//message
        }
        
        //fetch thread
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchMessagesThread";
                
        $header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$_SESSION['cookie']);

        $myvars='session_key='.$_SESSION['session_key'].'&recipient_email='.$recipient_email.'&limit='.$limit.'&skip='.$skip;

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

        $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message
        
        $list='';
        //draw
        if($check_is==true)//if check is true
        {
            $count=  count($message_is)-1;
            
            //read it in a reverse way
            for ($index = $count; $index >= 0; $index--) 
            {
                    $_id=$message_is[$index]['_id']['$oid'];
                    $header=$message_is[$index]['header'];
                    $content=$message_is[$index]['content'];
                    $attachment=$message_is[$index]['attachment'];
                    $time_stamp=$message_is[$index]['time_stamp'];
                    $sent_by=strtolower($message_is[$index]['sent_by']);
                    
                    $content_is=$content==null? 'No message': $content;
                    $attachment_is=$attachment==null? 'No attachment': '<a href="'.$attachment.'" title="Click to download in new tab" target="_blank">Attachment</a>';
                    
                    $me_or_Other_id=$sent_by=="me"? "sent_by_me": "sent_by_Other";
                    $list.='<li id="'.$me_or_Other_id.'">
                            <header_item>'.strtoupper($header).'</header_item><br>
                            <message_item>'.$content_is.'</message_item><br>
                            <attachment_item>'.$attachment_is.'</attachment_item><br> 
                                <date_item>'.return_date_function($time_stamp).'</date_item><br> 
                                       </li>';
            }
           
            
            $list='<ol id="messages_thread_list">'.$list.'</ol>';
        }
        else//else failed
        {
           
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
        
                        
                    $name=$_FILES['file']['name'];//file name
                    $type=$_FILES['file']['type'];//type of file
                    $temp=$_FILES['file']['tmp_name'];//temprorary location of files when uploaded

                    $file_url='';
                    //send message
                    if(isset($_POST['header']) && !empty($_POST['header'])  )
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
                                $header=trim($_POST['header']);

                                $content=trim($_POST['content']);
                                
                            
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.SendMessage";

                             $myvars='session_key='.$_SESSION['session_key'].'&header='.$header.'&content='.$content.'&attachment='.$file_url.'&to='.$recipient_email;

                            $header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$_SESSION['cookie']);

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                   
                                  header('location: '.$full_link.'&message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: '.$full_link.'&message='.$message_is.'&type=2');//
                            } 
                       
                    }
}

    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Messages view</title>
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
           
	     
		    
		   
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6">
                                    <h2><?php echo $recipient_name;?></h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
		 <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /><input type="submit" value="GO"/> 
                            </form><br>
                         
                           <?php echo $list;?><br><br>
                        </div>
                    </div>
                </div>
            </div>
			
			
		    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Compose a Message
                                
                            </h2>
                           
                        </div>
                        <div class="body">
                           
				 <?php echo $message;?><br>
                                   <form action="" method="POST" enctype="multipart/form-data" >
                                     <input type="text" name="header" placeholder="subject"/>
                                     <textarea name="content" cols="20" rows="12" placeholder="Message..." /></textarea>
                                   <input type="file" name="file"  title="Add attachment"/> 
                                      <button type="submit" class="btn btn-primary m-t-15 waves-effect">Send</button>
                                 </form>
                          
                           <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
                            <script type="text/javascript" src="../../javascript/are_you_huma_reload.js"></script>
				  
				   <br>
              </form>
		   
                        </div>
                    </div>
                </div>
		  
		  <a href="../../client/console/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br>
            <!-- #END# -->
			
			
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