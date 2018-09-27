<?php
require './functions.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



//policy
if(
isset($_POST['session']) && !empty($_POST['session']) &&
        isset($_POST['authorization']) && !empty($_POST['authorization']) &&
        isset($_POST['cookie']) && !empty($_POST['cookie']) &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['policy_id']) && !empty($_POST['policy_id']) &&
        isset($_POST['total']) && !empty($_POST['total']) 
        
        )
{
        $session=$_POST['session']; 
        $authorization=$_POST['authorization']; 
        $cookie=$_POST['cookie'];
        $email=$_POST['email'];
        $policy_id=$_POST['policy_id'];
        $total=$_POST['total'];
       
        
              $select=select_from_table_on_one_condition(temp_acc_info_table_name,'policy_id',$policy_id);
              $account_number=null;
              //echo json_encode($select);
               if(count($select)>0)// exists
               {
                  // echo 'lol';
                    $account_number=$select['account_number'];
               }
               else
               {
                   //make account
                   $account_number=make_account_number();
                   insert_into_temp_table(temp_acc_info_table_name,$session,$authorization,$cookie,$email,$policy_id,$total,$account_number,'policy','1979-12-01');
               }
                
               echo $account_number;
                
}               

//wallet
if(
isset($_POST['session']) && !empty($_POST['session']) &&
        isset($_POST['authorization']) && !empty($_POST['authorization']) &&
        isset($_POST['cookie']) && !empty($_POST['cookie']) &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['use_date']) && !empty($_POST['use_date']) &&
        isset($_POST['total']) && !empty($_POST['total']) 
        
        )
{
        $session=$_POST['session']; 
        $authorization=$_POST['authorization']; 
        $cookie=$_POST['cookie'];
        $email=$_POST['email'];
        $use_date=$_POST['use_date'];
        $total=$_POST['total'];
       
        
              //$select=select_from_table_on_one_condition(temp_acc_info_table_name,'policy_id',$policy_id);
              $select=select_from_table_on_two_condition(temp_acc_info_table_name,'email',$email,'type','wallet');
              
              $account_number=null;
              //echo json_encode($select);
               if(count($select)>0)// exists
               {
                  // echo 'lol';
                    $account_number=$select['account_number'];
                    $id=$select['id'];
                    
                    
                    //update use date
                    update_table_on_one_conditions(temp_acc_info_table_name,'use_date',$use_date,'id',$id);
                    
               }
               else
               {
                   //make account
                   $account_number=make_account_number();
                   insert_into_temp_table(temp_acc_info_table_name,$session,$authorization,$cookie,$email,'none',$total,$account_number,'wallet',$use_date);
               }
                
               echo $account_number;
                
} 

function make_account_number()
{
    $run=true;
    
    while ($run==true) 
    {
            $random= rand(10001, 999999);
            
            //check
            $check= check_if_two_columns_exists(temp_acc_info_table_name, 'account_number', 'account_number', $random, $random);
            
            if($check==true)//doesnt exist
            {
                $run=false;
                return $random;
            }
    }
}






