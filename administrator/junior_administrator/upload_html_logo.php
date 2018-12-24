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


//fetch
$url_is=the_api_authentication_api_url_is()."denkimAPIFiles/MainPackages.ListInsurancePolicyImages";

$myvars='session_key='.$_SESSION['session_key'];

$header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/junior_administrator/upload_html_logo.php');

$returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

$returned_json_decoded= json_decode($returned_json,true);//decode

$check_is=$returned_json_decoded["check"];//check


//draw
if($check_is==true)//if check is true
{
    $message_is=$returned_json_decoded["message"];//message
    
    //$message_is=sort_files_by_date_dsc($message_is);
    
    $action_page='upload_html_logo.php?holder=null';
    
    if(isset($_GET['sort_by']))
    {
            $sort_by=$_GET['sort_by'];
            $message_is=sort_files_accordingly($sort_by,$message_is);	
    }
    else
    {
        $message_is=sort_files_accordingly('last_modified',$message_is);
    }

    $s_set_limit=isset($_POST['s']) || !empty($_POST['s'])?$_POST['s']: $_GET['s'];

    $clk_set_limit=$_GET['clk'];
    $p_set_limit=$_GET['p'];
    $sort_by_set_limit=$_GET['sort_by'];


    //setting limits
    $limits=limit_view_tables_function(''.$action_page.'&blank=0', $message_is, $s_set_limit,$clk_set_limit,$p_set_limit,$sort_by_set_limit);

    $loop_start_pos=$limits[0];//loop start location
    $max_loop_size=$limits[1];//max loop size
    $browse_links=$limits[2];//browse items
    
                             //$count=0;
                             
                             for($count=$loop_start_pos;$count<$max_loop_size;$count++)
                             {//start of $count=$loop_start_pos;$count<$max_loop_size;$count++)
                                 $value=$message_is[$count];
                                 
                                    $file_name=$value['name'];
                                    $last_modified=$value['last_modified'];
                                    $link=$value['link'];
                                    $size=$value['size'];
                                    
                                    $id="word_copy_".$count;

                                   $row_color=$count%2;
                                   $row_color=$row_color==0?'odd':'even';

                                   $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                                 <td>'.($count+1).'</td>  
                                                                                 <td id="file_name_td'.$count.'" >'.$file_name.'</td>
                                                                                 <td id="size_td'.$count.'" >'.round(bytes_to_megabytes($size),3,PHP_ROUND_HALF_UP) .' MB</td>  
                                                                                 <td id="link_td'.$count.'" ><img src="'.$link.'" width="60" height="60" alt="'.$link.'" title="Click to copy link." style="cursor: pointer;" onclick="myFunction(\''.$id.'\');" /><input type="text" value="'.$link.'" id="'.$id.'" /></td>   
                                                                                 <td id="last_modified_td'.$count.'" >'.return_date_function($last_modified).'</td> 
                                                                     </tr>';
                                   
                                   $total_payments+=$amount_paid;//add to total payments
                                   //$count++;
                             }//end of //start of $count=$loop_start_pos;$count<$max_loop_size;$count++)
                             
                             
                             $table_head='<tr bgcolor="white">
                                          <th>#</th>
                                             <th><a href="'.$action_page.'&sort_by=name&s='.$s_set_limit.'"onmouseover="hover_link(\'file_name_td\',\''.$count.'\');" onmouseout="out_link(\'file_name_td\',\''.$count.'\');" >File name</a></th>
                                             <th><a href="'.$action_page.'&sort_by=size&s='.$s_set_limit.'"onmouseover="hover_link(\'size_td\',\''.$count.'\');" onmouseout="out_link(\'size_td\',\''.$count.'\');" >Size</a></th>
                                             <th><a href="'.$action_page.'&sort_by=link&s='.$s_set_limit.'" onmouseover="hover_link(\'link_td\',\''.$count.'\');" onmouseout="out_link(\'link_td\',\''.$count.'\');" >Link</a></th>
                                             <th><a href="'.$action_page.'&sort_by=last_modified&s='.$s_set_limit.'" onmouseover="hover_link(\'last_modified_td\',\''.$count.'\');" onmouseout="out_link(\'last_modified_td\',\''.$count.'\');" >Date</a></th>
                                             
                                        </tr>';
                             
                             $table=$browse_links.'<table class="table table-bordered table-hover table-responsive">'.$table_head.$table.'
                                          </table>'.$browse_links;
                             
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



$name=$_FILES['file']['name'];//file name
$type=$_FILES['file']['type'];//type of file
$temp=$_FILES['file']['tmp_name'];//temprorary location of files when uploaded


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
    $url_is=the_api_authentication_files_url_is()."denkimAPIFiles/MainPackages.UploadInsurancePolicyImage?session_key=".$_SESSION['session_key'];

    $myvars=array('file'=> $cfile );
    
    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/junior_administrator/upload_html_logo.php');

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
            header('location: upload_html_logo.php?message='.$message_is.': '.$file.'&type=1');//
    }
    else//else failed
    {
            //remove file
            unlink($location.$name);
            rmdir($location);
            header('location: upload_html_logo.php?message='.$message_is.'&type=2');//
    } 
}


    
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Upload policy logo</title>
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
                                    <h2> <li>UPLOAD POLICY LOGO&nbsp;&nbsp;&nbsp;</li><li><a href="upload_html_logo_html.php" title="Upload HTML">UPLOAD POLICY HTML</a></li><li><a href="upload_html_logo_zip.php" title="Upload Zip">UPLOAD POLICY ZIP</a></li></h2>
                                     
                                </div>
                               </div>
                         </div>
                        <div class="body">
						<?php echo $message;?><br>
		      <form method="post" action="" enctype="multipart/form-data" > 
            <input type="file" name="file"  /> 
            <button type="submit" class="btn btn-primary m-t-15 waves-effect">upload</button> 
            </form><br><br>
             <?php echo $table;?><br>
             <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
                           <script type="text/javascript" src="../../javascript/copy_to_clipboard.js"></script>
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