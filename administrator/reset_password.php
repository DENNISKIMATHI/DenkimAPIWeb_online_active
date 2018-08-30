<?php
require '../le_functions/sessions.php';
require '../le_functions/functions.php';

//setting edit message
if(isset($_GET['message']) && !empty($_GET['message']) && isset($_GET['type']) && !empty($_GET['type']))
{
	$message=$_GET['message'];
        $type=$_GET['type'];
        $good_bad_id=$type==1? 'good_upload_message': 'bad_upload_message';
	$message='<span id="'.$good_bad_id.'">'.$message.'</span>';
}

if(isset($_POST['reset_key']) && !empty($_POST['reset_key']) && isset($_POST['new_password']) && !empty($_POST['new_password']) & isset($_POST['confirm_password']) && !empty($_POST['confirm_password']))
{
	$reset_key=  trim($_POST['reset_key']);
        $new_password=trim($_POST['new_password']);
        $confirm_password=trim($_POST['confirm_password']);
        
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorResetPassword";
        
        $myvars='reset_key='.$reset_key.'&new_password='.$new_password.'&confirm_password='.$confirm_password;
        
        $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$_SESSION['Set-Cookie'],'Origin:/administrator/reset_password.php');
       
        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        $message_is=$returned_json_decoded["message"];//message
        
        if($check_is==true)//if login is true
        {
            session_destroy();// kill the session
             header('location: index.php?message='.$message_is.'&type=1');//
        }
        else//else failed
        {
            
            header('location: reset_password.php?message='.$message_is.'&type=2');//
        }
}

//check login
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Reset password | Denkim</title>
    <!-- Favicon-->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="../plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="../plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="../plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="../css/style.css" rel="stylesheet">
	 <link href="../css/main.css" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
       
        <div class="card">
            <div class="body">
			 <div class="logo">
               <a href="javascript:void(0);"><img src="../images/logo.png" alt="Denkim insurance"  width="300"></a>
        </div>
		   <br>   
               
        <form method="POST" action="">
                    <div class="msg"> <?php echo $message;?></div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">vpn_key</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control"  name="reset_key" placeholder="Reset key" required autofocus>
							 
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
						
						</div>
						 <div class="input-group">
						<span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
						<div class="form-line">
                            <input type="password" name="confirm_password"  placeholder="Confirm password" class="form-control"  required>
                        </div>
                    </div>
                    <div class="row">
                       
                     
                            <button class="btn btn-block bg-pink waves-effect" type="submit" value="login">Reset password</button>
                        <br>
						<br>
                    </div>
					 <div class="row">
                        <div class="col-xs-8 p-t-5">
                             <a href="forgot_password.php" title="Get a password reset key and get a new password">Forgot password?</a><br>
                        </div>
                        <div class="col-xs-4">
                             <a href="index.php" title="Go to login page">Login</a>
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="../plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core Js -->
    <script src="../plugins/bootstrap/js/bootstrap.js"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="../plugins/node-waves/waves.js"></script>

    <!-- Validation Plugin Js -->
    <script src="../plugins/jquery-validation/jquery.validate.js"></script>

    <!-- Custom Js -->
    <script src="../js/admin.js"></script>
    <script src="../js/pages/examples/sign-in.js"></script>
</body>

</html>