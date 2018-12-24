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
                    if(isset($_POST['national_id']) && !empty($_POST['national_id']) && 
                    isset($_POST['full_names']) && !empty($_POST['full_names']) &&
                    isset($_POST['email_address']) && !empty($_POST['email_address']) &&
                    isset($_POST['phone_number']) && !empty($_POST['phone_number']) &&
                    isset($_POST['antispam']) && !empty($_POST['antispam']) 
                    )
                    {  
                        $national_id=trim($_POST['national_id']);
                        $full_names=trim($_POST['full_names']);
                        $email_address=trim($_POST['email_address']);
                        $phone_number=trim($_POST['phone_number']);
                        $antispam=trim($_POST['antispam']);

                        if($antispam==$_SESSION['spam'])
                        {
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.CreateClient";

                            $myvars='national_id='.$national_id.'&full_names='.$full_names.'&email_address='.$email_address.'&phone_number='.$phone_number;

                            $header_array= array('Authorization:'.api_key_is(),'Origin:/client/signup.php');

                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                            $returned_json_decoded= json_decode($returned_json,true);//decode

                            $check_is=$returned_json_decoded["check"];//check

                            $message_is=$returned_json_decoded["message"];//message

                            if($check_is==true)//if check is true
                            {
                                  
                                  header('location: signup.php?message='.$message_is.'&type=1');//
                            }
                            else//else failed
                            {

                                header('location: signup.php?message='.$message_is.'&type=2');//
                            } 
                        }
                        else
                        {
                             header('location: signup.php?message=Failed anti-spam test, please try again&type=2');//

                        }

                    }
 


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign Up | Denkim</title>
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

<body class="signup-page">
    <div class="signup-box">
       
        <div class="card">
            <div class="body">
			<div class="logo">
               <a href="javascript:void(0);"><img src="../images/logo.png" alt="Denkim insurance"  width="300"></a>
        </div>
		 <?php echo $message;?><br>
                <form action="" method="POST">
                    <div class="msg">Register a new membership</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="full_names" placeholder="Full names" required autofocus>
							
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">email</i>
                        </span>
                        <div class="form-line">
                            <input type="email" class="form-control" name="email_address" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">contact_phone</i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control" name="phone_number"  minlength="6" placeholder="254722333444" required>
							 
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">assignment </i>
                        </span>
                        <div class="form-line">
                            <input type="number" class="form-control" name="national_id"  minlength="4" placeholder="National ID" required>
							
                        </div>
                    </div>
                    <div class="form-group">
                       <img src="../le_functions/_antispam.php" id="are_you_human" /><a href="" onclick="reload_are_you_human('are_you_human');">Reload</a><br><br>
					<input type="text" id="antispam" name="antispam" placeholder="key in the above numbers?"/>
                    </div>

                    <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit">SIGN UP</button>

                    <div class="m-t-25 m-b--5 align-center">
                       <a href="../index.php">Home</a>
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
    <script src="../js/pages/examples/sign-up.js"></script>
</body>

</html>