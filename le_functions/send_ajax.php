<?php
require './sessions.php';
require './functions.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

try 
{
        $post_data= file_get_contents('php://input');


        if(isset($post_data) && !empty($post_data))
        {
            $data_is=trim($post_data);
            $data_is_array=  json_decode($data_is,true);
            
            switch ($data_is_array['work']) 
            {
                    case 'combined_total':

                        $array=array();
                        
                        $array['check']=true;
                        $array['message']=null;
                        
                        //fetch email
                        $personal_details_array=fetch_personal_details(3,$_SESSION['session_key'],$_SESSION['cookie'],'/client/console/index.php');
                        $email_address=$personal_details_array['email_address'];
        
                        $total=0;
                        $payment=0;
                        $balance=0;

                        //get payment combined
                            //fetch

                                    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyFetchCombinedAll";

                                    $myvars='session_key='.$_SESSION['session_key'].'&email='.$email_address;

                                    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:/client/console/index.php');

                                   $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                                   
                                    $returned_json_decoded= json_decode($returned_json,true);//decode

                                    $check_is_2=$returned_json_decoded["check"];//check
                                    
                                   // echo $check_is_2.'=='.$policy_id.'<br>';
                                    if($check_is_2==true)//if check is true
                                    {//if($check_is_2==true)//if check is true
                                         $message_is_now=$returned_json_decoded["message"];//message
                                         $totals_info=get_aggregate_totals_payments_client_full_json($message_is_now);

                                         $total=$totals_info['total'];
                                        $payment=$totals_info['payment'];
                                        $balance=$totals_info['balance']==0?$totals_info['total']:$totals_info['balance'];


                                    }//if($check_is_2==true)//if check is true
                        
                                    $credit=0;
                                    $show_balance=$balance;
                                    if($balance<0)
                                    {
                                        $credit=$balance;
                                        $show_balance=0;
                                    }
                                    
                                    
                                    $array['message']=array('total'=>number_format($total),'payment'=>number_format($payment),'balance'=>number_format($balance),'credit'=>number_format($credit),'show_balance'=>number_format($show_balance));
                                    
                        echo json_encode($array);
                    break;

                    default:
                    break;
            }
                /*
                $url_is=the_api_requests_url().$data_is_array['url'];
       
                $myvars='session_id='.$_SESSION['session_id'].'&'.$data_is_array['data'];

                $header_array= array('Cookie:'.$_SESSION['cookie']);

                $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

               

                echo $returned_json;
                 * 
                 */
            
        }

} 
catch (Exception $exc) 
{
    //$array_response=array("returnCode"=>3,"returnMessage"=>"Fail error.");
    //echo json_encode($array_response);
}