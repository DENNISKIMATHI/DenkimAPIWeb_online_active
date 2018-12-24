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
        
        
        $full_link="clients_information.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $link_without_sort_column_sort_order="clients_information.php?l=".$limit."&s=".$skip."&re=".$rows_every;//for headers sorting
        $link_without_limit_skip_rows_every="clients_information.php?sc=".$sort_column."&so=".$sort_order;//for browsing
        $delete_link="clients_information_delete.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $edit_link="clients_information_edit.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
        $add_remove_link="clients_information_add_remove.php?l=".$limit."&s=".$skip."&sc=".$sort_column."&so=".$sort_order."&re=".$rows_every;//for form submission
       
      
        
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
        
       
        
        
                    //submit
                    if(isset($_POST['client_name']) && !empty($_POST['client_name']) && 
                    isset($_POST['item_type']) && !empty($_POST['item_type']) &&
                    isset($_POST['company']) && !empty($_POST['company']) &&
                    isset($_POST['class_of_insurance']) && !empty($_POST['class_of_insurance']) &&
                    isset($_POST['policy_number']) && !empty($_POST['policy_number']) &&
                    isset($_POST['preimium_charged']) && !empty($_POST['preimium_charged']) &&
                    isset($_POST['renewal_date']) && !empty($_POST['renewal_date']) &&
                    isset($_POST['sum_insured']) && !empty($_POST['sum_insured']) 
                    )
                    {  
                       
                        $client_name=trim($_POST['client_name']);
                        $item_type=trim($_POST['item_type']);
                        $company=trim($_POST['company']);
                        $class_of_insurance=trim($_POST['class_of_insurance']);
                        $policy_number=trim($_POST['policy_number']);
                        $preimium_charged=trim($_POST['preimium_charged']);
                        $renewal_date=trim($_POST['renewal_date']);
                        $sum_insured=trim($_POST['sum_insured']);

                        
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorCreateClientInsuranceInformation";

                            $myvars='session_key='.$_SESSION['session_key'].
                                    '&client_name='.$client_name.
                                    '&item_type='.$item_type.
                                    '&company='.$company.
                                    '&class_of_insurance='.$class_of_insurance.
                                    '&policy_number='.$policy_number.
                                    '&preimium_charged='.$preimium_charged.
                                    '&renewal_date='.$renewal_date.
                                    '&sum_insured='.$sum_insured;

                            $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/clients.php');

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
     
                    
                    
                    //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchClientInsuranceInformationDetails";

        $myvars='session_key='.$_SESSION['session_key'].'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;

        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/senior_administrator/clients.php');

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
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=client_name&so='.return_script_order($sort_column,$sort_order,"client_name").'"onmouseover="hover_link(\'client_name_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'client_name_td\',\''.$total_for_table_rows.'\');" >Client name</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=item_type&so='.return_script_order($sort_column,$sort_order,"item_type").'"onmouseover="hover_link(\'item_type_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'item_type_td\',\''.$total_for_table_rows.'\');" >Item type</a></th>
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=company&so='.return_script_order($sort_column,$sort_order,"company").'" onmouseover="hover_link(\'company_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'company_td\',\''.$total_for_table_rows.'\');" >Company</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=class_of_insurance&so='.return_script_order($sort_column,$sort_order,"class_of_insurance").'" onmouseover="hover_link(\'class_of_insurance_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'class_of_insurance_td\',\''.$total_for_table_rows.'\');" >Insurance class</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=policy_number&so='.return_script_order($sort_column,$sort_order,"policy_number").'" onmouseover="hover_link(\'policy_number_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'policy_number_td\',\''.$total_for_table_rows.'\');" >Policy number</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=preimium_charged&so='.return_script_order($sort_column,$sort_order,"preimium_charged").'" onmouseover="hover_link(\'preimium_charged_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'preimium_charged_td\',\''.$total_for_table_rows.'\');" >Premium</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=sum_insured&so='.return_script_order($sort_column,$sort_order,"sum_insured").'" onmouseover="hover_link(\'sum_insured_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'sum_insured_td\',\''.$total_for_table_rows.'\');" >Sum insured</a></th>
                                
                                <th><a href="'.$link_without_sort_column_sort_order.'&sc=renewal_date&so='.return_script_order($sort_column,$sort_order,"renewal_date").'" onmouseover="hover_link(\'renewal_date_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'renewal_date_td\',\''.$total_for_table_rows.'\');" >Renewal date</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=shared_with&so='.return_script_order($sort_column,$sort_order,"shared_with").'" onmouseover="hover_link(\'shared_with_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'shared_with_td\',\''.$total_for_table_rows.'\');" >Share count</a></th>
                            <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'time_stamp_td\',\''.$total_for_table_rows.'\');" >Date</a></th>
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'add_juniors_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'add_juniors_td\',\''.$total_for_table_rows.'\');" >Share</a></th>
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'edit_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'edit_td\',\''.$total_for_table_rows.'\');" >Edit</a></th>
                             <th><a href="'.$link_without_sort_column_sort_order.'&sc=time_stamp&so='.return_script_order($sort_column,$sort_order,"time_stamp").'" onmouseover="hover_link(\'delete_td\',\''.$total_for_table_rows.'\');" onmouseout="out_link(\'delete_td\',\''.$total_for_table_rows.'\');" >Delete</a></th>
                            
                            </tr>';
            $from_one_counter=1;//used to know how many rows are printed from one so as to append table head
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                 $_id=$value['_id']['$oid'];
                  $client_name=$value['client_name'];
                  $item_type=$value['item_type'];
                  $company=$value['company'];
                  $class_of_insurance=$value['class_of_insurance'];
                  $policy_number=$value['policy_number'];
                  $preimium_charged=$value['preimium_charged'];
                  $renewal_date=$value['renewal_date'];
                  $shared_with=$value['shared_with'];
                  $time_stamp=$value['time_stamp'];
                  $sum_insured=$value['sum_insured'];
                  
                  
                  $row_color=$count%2;
                  $row_color=$row_color==0?'odd':'even';
                             
                  $explode_renewal_date= explode('-', $renewal_date);
                  
                  $full_edit_link=$edit_link.'&client_name='.$client_name.
                                    '&item_type='.$item_type.
                                    '&company='.$company.
                                    '&class_of_insurance='.$class_of_insurance.
                                    '&policy_number='.$policy_number.
                                    '&preimium_charged='.$preimium_charged.
                                    '&renewal_date='.$renewal_date.
                                    '&_id='.$_id.'&sum_insured='.$sum_insured;
                  
                  $full_add_remove_link=$add_remove_link.'&client_name='.$client_name.
                                    '&item_type='.$item_type.
                                    '&company='.$company.
                                    '&class_of_insurance='.$class_of_insurance.
                                    '&policy_number='.$policy_number.
                                    '&preimium_charged='.$preimium_charged.
                                    '&renewal_date='.$renewal_date.
                                    '&_id='.$_id.'&sum_insured='.$sum_insured.
                                    '&shared_with='.base64_encode(json_encode($shared_with))
                                    
                                        ;
                  
                  $table=$table.'<tr class="'.$row_color.'" id="row_data">
                                                <td>'.($count+1).'</td>  
                                                                <td id="client_name_td'.$count.'" >'.$client_name.'</td>
                                                                <td id="item_type_td'.$count.'" >'.$item_type.'</td>
                                                                <td id="company_td'.$count.'" >'.$company.'</td>  
                                                                <td id="class_of_insurance_td'.$count.'" >'.$class_of_insurance.'</td>   
                                                                <td id="policy_number_td'.$count.'" >'.$policy_number.'</td>   
                                                                <td id="preimium_charged_td'.$count.'" >'.number_format($preimium_charged,2).'</td>   
                                                                    <td id="sum_insured_td'.$count.'" >'.number_format($sum_insured,2).'</td>  
                                                                <td id="renewal_date_td'.$count.'" >'.$explode_renewal_date[2].'-'.$explode_renewal_date[1].'-'.$explode_renewal_date[0].'</td> 
                                                                <td id="shared_with_td'.$count.'" >'.count($shared_with).'</td>  
                                                                <td id="time_stamp_td'.$count.'" >'.return_date_function($time_stamp).'</td> 
                                                                <td id="add_juniors_td'.$count.'" ><a href="'.$full_add_remove_link.'" title="Share this entry with junior admins">Share</a></td>
                                                                <td id="edit_td'.$count.'" ><a href="'.$full_edit_link.'" title="Edit this entry">Edit</a></td> 
                                                                <td id="delete_td'.$count.'" ><span id="red_text_span"><a href="'.$delete_link.'&_id='.$_id.'&cn='.$client_name.'" title="Remove this entry">Delete</a></span></td>   
                                                                    
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
        <title>Clients information</title>
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
                <a class="navbar-brand" href="../senior_administrator/" title="Go to the main page"><img src="../../images/logo.png" alt="Denkim insurance" height="50" width="200"></a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Senior</div>
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
                       <a href="../senior_administrator/" title="Go to the main page">
                            <i class="material-icons">home</i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li>
                       <a href="junior_administrators.php?c=2&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete junior admins">
                            <i class="material-icons">person</i>
                            <span>Junior Administrators</span>
                        </a>
                    </li> 
                    <li>
                       <a href="claim_handlers.php?c=3&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete claim handlers">
                            <i class="material-icons">person</i>
                            <span>Claim handlers</span>
                        </a>
                    </li>
                    <li>
                       <a href="assessor_loss_adjsuter.php?c=4&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete Assessors/Loss adjusters">
                            <i class="material-icons">person</i>
                            <span>Assessors/Loss adjusters</span>
                        </a>
                    </li> 
                    <li>
                       <a href="repair_garage.php?c=5&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete repair garages">
                            <i class="material-icons">person</i>
                            <span>Repair garages</span>
                        </a>
                    </li> 
                    <li>
                       <a href="towing_rescue.php?c=6&l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete towing and rescue">
                            <i class="material-icons">person</i>
                            <span>Towing and rescue</span>
                        </a> 
                    </li>
                    <li>
                        <a href="clients.php?l=10&s=0&sc=time_stamp&so=dsc&re=100" title="Add or delete clients, view their policies, select, delete and edit policies for them, create and delete claims, also make, veiw, edit and delete payments">
                            <i class="material-icons">contacts</i>
                            <span>Clients</span>
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
                        <a href="insurance_policies.php" title="Add and delete insurance policies">
                            <i class="material-icons">accessible</i>
                            <span>Insurance Policies</span>
                        </a>
                    </li>
                  <li>
                       <a href="mobile_payments.php?l=10&s=0&sc=seen_status&so=asc&re=100" title="View made mobile payments and assign them to correct client policies">
                            <i class="material-icons">account_balance_wallet</i>
                            <span>Mobile Payments</span>
                         <?php echo get_mobile_payments_count_function($_SESSION['session_key'],$_SESSION['cookie'],'/senior_administrator/*');?></a>
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
                                    <h2>Clients</h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
			     <?php echo $message;?><br>
          <form action="<?php echo $full_link;?>" method="POST">
            <input type="text" name="client_name" required placeholder="Client name"/>
            <input type="text" name="item_type" required placeholder="Item type"/>
            <input type="text" name="company" required placeholder="Company name"/>
            <input type="text" name="class_of_insurance" required  placeholder="Class of insurance"/>
            <input type="text" name="policy_number" required placeholder="Policy number"/>
            <input type="number" name="preimium_charged" required placeholder="Premium charged"/>
            <input type="number" name="sum_insured" required placeholder="Sum insured"/>
            <input type="date" name="renewal_date" required placeholder="Renewal date"/><br>
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
                                    <h2>Edit client information</h2>
                                </div>
                               </div>
                         </div>
                        <div class="body">
		 <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form><br>
                           <?php echo $table;?><br>
                             <form method="POST" action="<?php echo $full_link;?>" id="browse_form">
                                Start row: <input type="number" name="skip_is" min="0" value="<?php echo $skip;?>" />  Number of rows: <input type="number" name="limit_is" min="1" value="<?php echo $limit;?>" /> Headers every: <input type="number" name="headers_is" min="10" value="<?php echo $rows_every;?>" /> rows <input type="submit" value="GO"/> 
                            </form>

                           <script type="text/javascript" src="../../javascript/jquery-1.11.1.min.js"></script>
                           <script type="text/javascript" src="../../javascript/highlight.js"></script>
                            <script type="text/javascript" src="../../javascript/are_you_huma_reload.js"></script>
		
		 
                        </div>
                    </div>
                </div>
            </div>
			 <a href="../senior_administrator/" title="Go to the main page" class="btn btn-primary m-t-15 waves-effect"> <i class="material-icons">arrow_back</i>Back </a><br><br> 
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