<?php
require './sessions.php';
require './functions.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchDetails";
       
        $myvars='session_key='.$_SESSION['session_key'];
        
        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/le_functions/keep_alive.php');
       
      echo  $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output