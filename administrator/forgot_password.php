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

if(isset($_POST['username']) && !empty($_POST['username']) )
{
	$username=  trim($_POST['username']);
        
        
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorForgotPassword";
        
        $myvars='username='.$username;
        
        $header_array= array('Authorization:'.api_key_is(),'Origin:/administrator/forgot_password.php');
       
        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        $message_is=$returned_json_decoded["message"];//message
        $cookie_is=$returned_json_decoded["Set-Cookie"];//cookie
         
        if($check_is==true)//if login is true
        {
             //session_destroy();// kill the session
             unset($_SESSION['Set-Cookie']);
             $_SESSION['Set-Cookie']=$cookie_is;  //set cookie session 
             header('location: reset_password.php?message='.$message_is.'&type=1');//
        }
        else//else failed
        {
            
            header('location: forgot_password.php?message='.$message_is.'&type=2');//
        }
}

//check login
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Forgot Password | Denkim</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

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

<body class="fp-page">
    <div class="fp-box">
     
        <div class="card">
            <div class="body">
			<div class="logo">
            <a href="javascript:void(0);"><img src="../images/logo.png" alt="Denkim insurance"  width="300"></a>
        </div>
                 <form method="POST" action="">
                    <div class="msg">
                      <br> <br><br>
					  <?php echo $message;?>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                        </div>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">Get reset key</button>

                    <div class="row m-t-20 m-b--5 align-center">
					 <a href="reset_password.php" title="Use your reset key to get a new password">Have a reset key?</a><br><br>
                       <a href="index.php" title="Go to login page">Login</a>
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
    <script src="../js/pages/examples/forgot-password.js"></script>
</body>

</html>