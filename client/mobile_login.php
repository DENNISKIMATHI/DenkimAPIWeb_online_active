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


    
    
     //submit
                    if(isset($_POST['username']) && !empty($_POST['username']) 
                    )
                    {  
                        $username=trim($_POST['username']);
                       
                            
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.ClientLoginMobile";

                            $myvars='username='.$username;
                            
                            $header_array= array('Authorization:'.api_key_is(),'Origin:/client/mobile_login.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output


                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                    $_SESSION['session_key']=$returned_json_decoded['session_key'];
                                    $_SESSION['cookie']=$returned_json_decoded['Cookie_is'];
                                  header('location: mobile_login_process.php?message='.$returned_json_decoded['message'].'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: mobile_login.php?message='.$returned_json_decoded['message'].'&type=2');//
                            } 
                             
                        
                    }


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | denkim</title>
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
	 
	 <style type="text/css">

   html,body{
       background:url(../images/bg1.jpg)
       no-repeat center center fixed;
       -webkit-background-size:cover;
       -o-background-size:cover;
       -moz-background-size:cover;
       background-size:cover;
       height: 100%;
		}



</style>
	 
	 
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
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="username" placeholder="email" required autofocus>
							 
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                             <a href="../index.php">Home</a>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit" value="login">SIGN IN</button>
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