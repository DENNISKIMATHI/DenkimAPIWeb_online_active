<?php

//database
define("database_name_is","denkimin_payments");
define("database_password_is","B834#@G#Tter#1");
define("database_user_name_is","denkimin");
define("database_host_name_is","localhost");

define("temp_acc_info_table_name","temp_acc_info");

function connect_to_database_function()//requires host name, user name ,pass word and database
{
        
            if (!mysqli_connect(database_host_name_is,database_user_name_is,database_password_is,database_name_is))
            {
                die("Could not connect to database".'--'.database_host_name_is.'--'.database_user_name_is.'--'.database_password_is.'--'.database_name_is."\n");	// kill script incase of error
            }
            else
            {
                return mysqli_connect(database_host_name_is,database_user_name_is,database_password_is,database_name_is);
            }

}


//script to check if a certain value exists using two column values
function check_if_two_columns_exists($TableName,$ColumnData1,$ColumnData2,$MatchWith1,$MatchWith2)
{
        
        //connecting to database
        $Connection=connect_to_database_function();
 
        $query="SELECT `$ColumnData1` , `$ColumnData2` FROM `$TableName` WHERE `$ColumnData1` = '".mysqli_real_escape_string($Connection,$MatchWith1)."' AND `$ColumnData2` = '".mysqli_real_escape_string($Connection,$MatchWith2)."'"; 
        
        $myquery=mysqli_query($Connection,$query);
        $num=mysqli_num_rows($myquery);
       
        if($num>=1)
        {
            
             $Connection->close();
              mysqli_free_result($myquery);
             return false;// if the match is found 
        }
        else 
        {
             $Connection->close();
              mysqli_free_result($myquery);
             return true;// if the match is not found

        }

} 



function select_from_table_on_one_condition($TableName,$ConditionColumn,$ConditionValue)
{
   
        //connecting to database
        $Connection=connect_to_database_function();
 
        $my_county_select=$my_county_select="SELECT *
		                        FROM `$TableName` WHERE 
		                        `$ConditionColumn`='".mysqli_real_escape_string($Connection,$ConditionValue)."' ";
								
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
                
                if($do_my_county_select)
                {
                    
                        $selected_manage_data= mysqli_fetch_assoc($do_my_county_select);
                       
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select on one condition");
                }

}

function select_from_table_on_two_condition($TableName,$ConditionColumn_1,$ConditionValue_1,$ConditionColumn_2,$ConditionValue_2)
{
   
        //connecting to database
        $Connection=connect_to_database_function();
 
        $my_county_select=$my_county_select="SELECT *
		                        FROM `$TableName` WHERE 
		                        `$ConditionColumn_1`='".mysqli_real_escape_string($Connection,$ConditionValue_1)."' AND  
		                        `$ConditionColumn_2`='".mysqli_real_escape_string($Connection,$ConditionValue_2)."' ";
								
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
                
                if($do_my_county_select)
                {
                    
                        $selected_manage_data= mysqli_fetch_assoc($do_my_county_select);
                       
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select on two condition");
                }

}


//insert into insert temp
function insert_into_temp_table($TableName,$session,$authorization,$cookie,$email,$policy_id,$total,$account_number,$type,$use_date)
{
   
        //connecting to database
        $Connection=connect_to_database_function();

        
        
        
         $insert_into_table ="INSERT INTO `$TableName`(`session`,`authorization`,`cookie`,`email`,`policy_id`,`total`,`account_number`,`type`,`use_date`) 
                                          VALUES ('$session','$authorization','$cookie','$email','$policy_id','$total','$account_number','$type','$use_date')";

                                if($insert_into_table_query=mysqli_query($Connection,$insert_into_table))
                                {
                                        mysqli_free_result($insert_into_table_query);
                                        $Connection->close();
                                        return true;
                                }
                                else 
                                {
                                    die("could not insert into temp table");
                                }


}


function update_table_on_one_conditions($TableName,$set_column,$set_column_value,$check_column,$check_column_value)
{
        
        //connecting to database
        $Connection=connect_to_database_function();

       
        $update_ward_table_query="UPDATE `$TableName` SET 
                                   `$set_column`='$set_column_value'
                                   WHERE `$check_column`='$check_column_value' ";
                $do_my_ward_update=mysqli_query($Connection,$update_ward_table_query);
                if($do_my_ward_update)
                {

                        $Connection->close();
                        return true;
                }
                else 
                {
                       return false;
                    
                }

}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

