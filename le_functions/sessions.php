<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 86400);
// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(86400);
ob_start();//output buffer for the
session_start();// initiating session
//$current_file=$_SERVER['SCRIPT_NAME'];

if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
{
$http_referer=$_SERVER['HTTP_REFERER'];
}//setting the referer for redirection on logout




function loggedin()//fuction to check if a session is set if not false is returned otherwise a true
{
	if(isset($_SESSION['session_key']) && !empty($_SESSION['session_key']) && isset($_SESSION['cookie']) && !empty($_SESSION['cookie'])) 
	{
		return true;
	}
	else 
	{
		return false;
	}
}



