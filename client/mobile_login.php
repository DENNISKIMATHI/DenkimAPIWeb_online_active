

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
<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>Denkim | Login</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendor/linearicons/style.css">
	<!-- MAIN CSS -->
	<link rel="stylesheet" href="assets/css/main.css">
	<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
	<!-- GOOGLE FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
	<!-- ICONS -->
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box lockscreen clearfix">
					<div class="content">
						<h1 class="sr-only">Denkim</h1>
						<div class="logo text-center"><img src="assets/img/denkim.png" alt="denkim logo"></div>
						
						 <form method="POST" action="">
							<div class="form-group">
							<?php echo $message;?>
								<input type="email" class="form-control" name="username" placeholder="email" required autofocus> <br><br>
								<button type="submit" class="btn btn-primary btn-lg btn-block" style="text-align:centre;">LOGIN</button>
                                                                
                                                               
							</div>
						</form>
                                                <form method="GET" action="mobile_signup.php">
                                                 <button type="submit" class="btn btn-primary btn-lg btn-block" style="text-align:centre;">CREATE YOUR ACCOUNT</button>
                                                 </form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>

</html>
