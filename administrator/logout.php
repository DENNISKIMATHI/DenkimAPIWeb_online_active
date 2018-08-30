<?php
require '../le_functions/sessions.php';

//echo $http_referer;// show the page where the reference is from
$message=trim($_GET['message']);
$type=trim($_GET['type']);

try 
{
    session_destroy();// kill the session
    header('Location: ./?message='.$message.'&type='.$type.'');//return to where previous reference was from

} catch (Exception $exc) {
   
header('Location: ./?message='.$message.'&type='.$type.'');//return to where previous reference was from

}


?>
