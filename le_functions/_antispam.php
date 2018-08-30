<?php // content="text/plain; charset=utf-8"
// Antispam  using a random string
require_once "jpgraph/jpgraph_antispam.php";
require './sessions.php';

// Create new anti-spam challenge creator
// Note: Neither '0' (digit) or 'O' (letter) is used to avoid confusion
$spam = new AntiSpam();

// Create a random 6 char challenge and return the string generated

$char=$spam->Rand('3');

	$_SESSION['spam']= $char;// setting session to pass actual pattern to the register page i.e 'register.php'




// Stroke random challenge

if(  $spam->Stroke() === false ) {
	
   die('Illegal or no data to plot');
}


?>

