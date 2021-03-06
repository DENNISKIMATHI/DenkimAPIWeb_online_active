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




if( isset($_GET['l']) && is_numeric($_GET['l']) && ( $_GET['s']==0 || is_numeric($_GET['s']) ) )
{
	
        $limit=trim($_GET['l']);
        $skip=trim($_GET['s']);
        
        
        $full_link="tasks.php?l=".$limit."&s=".$skip;//for form submission
        $link_without_sort_column_sort_order="tasks.php?l=".$limit."&s=".$skip;//for headers sorting
        $link_without_limit_skip_rows_every="tasks.php";//for browsing
        $delete_link="tasks_delete.php?l=".$limit."&s=".$skip;//for form submission
        $edit_link="tasks_edit.php?l=".$limit."&s=".$skip;//for form submission
        
        
                    if(isset($_POST['task']) && !empty($_POST['task']) && 
                    isset($_POST['date_is']) && !empty($_POST['date_is']) &&
                    isset($_POST['time_is']) && !empty($_POST['time_is']) &&
                    isset($_POST['repeats']) && !empty($_POST['repeats'])  
                    )
                    {  
                        $task=trim($_POST['task']);
                        $date_is=trim($_POST['date_is']);
                        $time_is=trim($_POST['time_is']);
                        $repeats=trim($_POST['repeats']);

                            $reminder_date_time=$date_is.' '.$time_is.':00';



                            $url_is=the_api_for_php_is_url_is()."create_diary/";

                            $myvars='session_key='.$_SESSION['session_key'].
                                    '&authorization='.api_key_is().
                                    '&origin=Origin:/senior_administrator/tasks.php'.
                                    '&cookie='.$_SESSION['cookie'].
                                    '&task='.$task.
                                    '&reminder_date_time='.$reminder_date_time.
                                    '&repeats='.$repeats;

                             $header_array= array();

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
       
                    
              //form submission
                if(
                        isset($_POST['limit_is']) && !empty($_POST['limit_is']) && 
                        is_numeric($_POST['limit_is']) && 
                        ( $_POST['skip_is']==0 || is_numeric($_POST['skip_is']) ))
                {
                    $new_limit=trim($_POST['limit_is']);
                    $new_skip=trim($_POST['skip_is']);
                    
                    header('location: '.$link_without_limit_skip_rows_every.'?l='.$new_limit.'&s='.$new_skip.'');//redirect back to form correctly
                }
                
                
                
        //fetch
        $url_is=the_api_for_php_is_url_is()."fetch_diary/";

        $myvars='session_key='.$_SESSION['session_key'].
                                    '&authorization='.api_key_is().
                                    '&origin=Origin:/senior_administrator/tasks.php'.
                                    '&cookie='.$_SESSION['cookie'].
                                    '&limit='.$limit.
                                    '&skip='.$skip;

        $header_array= array();

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
                             <th><a href="" onmouseover="hover_link(\'task_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'task_td\',\''.$total_for_table_rows.'\');" >Tasks</a></th>
                            <th><a href="" onmouseover="hover_link(\'reminder_date_time_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'reminder_date_time_td\',\''.$total_for_table_rows.'\');" >Reminder at</a></th>
                             <th><a href="" onmouseover="hover_link(\'repeats_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'repeats_td\',\''.$total_for_table_rows.'\');" >Repeats</a></th>
                            <th><a href="" onmouseover="hover_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" >Created at</a></th>
                            <th><a href="" onmouseover="hover_link(\'edit_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'edit_td\',\''.$total_for_table_rows.'\');" >Edit</a></th>
                            <th><a href="" onmouseover="hover_link(\'delete_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'delete_td\',\''.$total_for_table_rows.'\');" >Delete</a></th>
                               
                            </tr>';
            
            $from_one_counter=1;//used to know how many rows are printed from one so as to append table head
            
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $_id=$value['_id'];
                  $task=$value['task'];
                  $reminder_date_time=$value['reminder_date_time'];
                  $repeats=$value['repeats'];
                  $time_stamp=$value['time_stamp'];
                  
                  
                  
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                                                         
                  $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                <td>'.($count+1).'</td>  
                                                                <td id="task_td'.$count.'" >'.$task.'</td>
                                                                <td id="reminder_date_time_td'.$count.'" >'.$reminder_date_time.'</td>
                                                                <td id="repeats_td'.$count.'" >'.return_task_repeats_array()[$repeats].'</td>  
                                                                <td id="time_stamp_td'.$count.'" >'.$time_stamp.'</td>  '
                          . '                                   <td id="edit_td'.$count.'" ><a href="'.$edit_link.'&_id='.$_id.'&task='.$task.'&reminder_date_time='.$reminder_date_time.'&repeats='.$repeats.'" title="Edit '.$task.'">Edit</a></td>
                                                                <td id="delete_td'.$count.'" ><a href="'.$delete_link.'&_id='.$_id.'&task='.$task.'&reminder_date_time='.$reminder_date_time.'&repeats='.$repeats.'" title="Remove '.$task.'">Delete</a></td>
                                                                    
                                                    </tr>';
                  //$table=$from_one_counter%$rows_every==0?$table.$table_head:$table;//if rows to add header is reached then add header
                  
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
     <title>Tasks</title>
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
           
                   <form action="<?php echo $full_link;?>" method="POST"/>
                   <textarea name="task" cols="20" rows="10" maxlength="" placeholder="Task" required></textarea><br><br>
                   <input type="date"  name="date_is" required /><br><br>
                   <input type="time" name="time_is" required /><br><br>
                   
                   <select name="repeats" required>
                       <option value="">Select repeat rate</option>
                       <?php
                        foreach (return_task_repeats_array() as $key=> $value) 
                        {
                            ?>
                          <option value="<?php echo $key;?>"><?php echo $value;?></option>
                            <?php
                        }
                       ?>
                   </select><br><br>
                   <input type="submit" value="Create"/>
                   </form>
                   
                   <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> <input type="submit" value="GO"/> 
                            </form><br>
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