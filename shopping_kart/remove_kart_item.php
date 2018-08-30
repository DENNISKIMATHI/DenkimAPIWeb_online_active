<?php
require '../le_functions/sessions.php';
require '../le_functions/functions.php';


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($_GET['item_id']) && !empty($_GET['item_id']) )
{
	$item_id=trim($_GET['item_id']);
        $shopping_kart=$_SESSION['shoping_cart'];
        $new_kart=array();
        $count=0;
        foreach ($shopping_kart as $key => $value) 
        {//start of foreach ($shopping_kart as $value)
                    $kart_item_array=$value;    
                    $type=$key;
                    foreach ($kart_item_array as $arrays) 
                    {
                             foreach ($arrays as $key => $value_items) 
                             {
                                $item_id_is=$key;//item id
                                //echo $item_id_is.'---';
                                    if($item_id!=$item_id_is)//if no match assign
                                    {
                                        $new_kart[$type][$count]=array($item_id_is=>$value_items);
                                        $count++;//counter
                                    }
                             }
                    }
        }//end of foreach ($shopping_kart as $value) 
       
        $_SESSION['shoping_cart']=$new_kart;//new kart
        header('location: ./?message=Policy removed from cart&type=1');//
}
else
{
    header('location: ./');
}