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










if( isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) &&  isset($_GET['sc']) && !empty($_GET['sc']) && isset($_GET['so']) && !empty($_GET['so']) && isset($_GET['re']) && !empty($_GET['re']))
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        $sort_column=trim($_GET['sc']);
        $sort_order=trim($_GET['so']);
        $rows_every=trim($_GET['re']);
        
        
        $full_link="clients.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $link_without_sort_column_sort_order="clients.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for headers sorting
        $link_without_limit_skip_rows_every="clients.php?sc=".$sort_column."&so=".$sort_order;//for browsing
       // $delete_link="clients_delete.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $clients_insurance="clients_insurance.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        //$claims="claims.php?l=10&s=0&re=10";//for form submission
       
      
        
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
            
             header('location: '.$link_without_limit_skip_rows_every.'&l='.$new_limit.'&s='.$new_skip.'&re='.$new_rows_every.' ');//redirect back to form correctly
        }
        
        //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchClientDetails";

        $myvars='session_key='.$_SESSION['session_key'].'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/junior_administrator/clients.php');

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
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=full_names&so='.return_script_order($sort_column,$sort_order,"full_names").'"onmouseover="hover_link(\'full_names_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'full_names_td\',\''.$total_for_table_rows.'\');" >Full names</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=email_address&so='.return_script_order($sort_column,$sort_order,"email_address").'"onmouseover="hover_link(\'email_address_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'email_address_td\',\''.$total_for_table_rows.'\');" >Email address</a></th>
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=phone_number&so='.return_script_order($sort_column,$sort_order,"phone_number").'" onmouseover="hover_link(\'phone_number_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'phone_number_td\',\''.$total_for_table_rows.'\');" >Phone number</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=national_id&so='.return_script_order($sort_column,$sort_order,"national_id").'" onmouseover="hover_link(\'national_id_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'national_id_td\',\''.$total_for_table_rows.'\');" >National id</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" >Registration date</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'insurance_policies_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'insurance_policies_td\',\''.$total_for_table_rows.'\');" >Insurance policies</a></th>
                             
                            </tr>';
            $from_one_counter=1;//used to know how many rows are printed from one so as to append table head
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  $phone_number=$value['phone_number'];
                  $national_id=$value['national_id'];
                  $time_stamp=$value['time_stamp'];
                  
                  
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                                                         
                  $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                <td>'.($count+1).'</td>  
                                                                <td id="full_names_td'.$count.'" >'.$full_names.'</td>
                                                                <td id="email_address_td'.$count.'" >'.$email_address.'</td>
                                                                <td id="phone_number_td'.$count.'" >'.$phone_number.'</td>  
                                                                <td id="national_id_td'.$count.'" >'.$national_id.'</td>   
                                                                <td id="time_stamp_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                <td id="insurance_policies_td'.$count.'" ><a href="'.$clients_insurance.'&e='.$email_address.'&f='.$full_names.'" title="View policies for '.$full_names.'">View</a></td>   
                                                                        
                                                    </tr>';
                  $table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header
                  
                  $count++;
                  $from_one_counter++;
            }//end of foreach $message_is as $value
            
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
        
        
                   
     
                    
}

    
//check login
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     <title>Clients</title>
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