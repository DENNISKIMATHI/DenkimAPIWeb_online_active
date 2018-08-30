<?php
require '../le_functions/sessions.php';
require '../le_functions/functions.php';

//fix cookie glictch on sms
if(isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['s']) && !empty($_GET['s']))
{
      $cookie=  trim($_GET['c']);
     //echo '<br>';
      $session=  trim($_GET['s']);
}
else 
{
     $session=  trim($_GET['s']);
    $ex= explode('JSESSIONID=', $session);
     $cookie=  'JSESSIONID='.$ex[1];
    //echo '<br>';
      $session=  $ex[0];
}
//submit
if(isset($session) && !empty($session) &&  isset($cookie) && !empty($cookie) )
{  
    
   
    
            //attempt to fetch personal info
            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchDetails";
       
        $myvars='session_key='.$session;
        
        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:/client/process_login.php');
       
        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
       
         
        if($check_is==true)//if check is true
        {
            
            $_SESSION['session_key']=$session;
            $_SESSION['cookie']=$cookie;
            header('location: ./console');//
            
        }
        else//else failed
        {
            $message_is=$returned_json_decoded["message"];//message
            
           // header('location: personal_information.php?message='.$message_is.'&type=2');//
            if($message_is=='')
            {
                header('location: login.php');//
            }
            else
            {
               header('location: ./login.php?message='.$message_is.'&type=2');//
            }
            
        }



}
else
{
     header('location: login.php');//
}

