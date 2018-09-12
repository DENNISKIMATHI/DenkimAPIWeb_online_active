<?php
function the_api_authentication_api_url_is()
{
    return 'http://35.184.46.252:6969/';
    //return 'http://localhost:8080/';
}

function the_api_authentication_files_url_is()
{
    return 'http://35.184.46.252:6969/';
    //return 'http://localhost:8080/';
}

function api_key_is()
{
    return '944e15799e5955f1d9ba5bc236daffe3ccdff2ebe7467c2a70e82d84eb42c30631e105cb0da98f00ac5ca7c793c03ccb';
}

function emai_address_for_admin()
{
    return 'info@denkiminsurance.com';
}

function return_unique_separator_email_name()
{
    $unique_separator=md5('email254uwillneverwritethisdown');
    
    return $unique_separator;
}
function send_curl_post($url,$myvars,$header_array)
{
   
    $ch = curl_init( $url );//initialize response
    
   //$array=array('url'=>$url,'myvars'=>$myvars,'header_array'=>$header_array);
   //$data_string=json_encode($array);
   
    
        //send to channel
        
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);//ignore sign in
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);//ignore sign in
        curl_setopt( $ch, CURLOPT_POST, 0);//as post
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);//set fields
       curl_setopt($ch, CURLOPT_HTTPHEADER, $header_array); 
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );//true to url
        curl_setopt( $ch, CURLOPT_HEADER, 0 );//header null
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);//catch the response
    
     //  $ch = curl_init('http://35.184.46.252/DenkimAPIWeb/channel_traffic.php');           
      //curl_setopt( $ch, CURLOPT_POST, 1);//as post                                                                   
      //curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);                                                                      
      //curl_setopt($ch, CURLOPT_HEADER, 0);                                                                                                                   
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//catch the response  
        
       //echo curl_exec($ch);
       
        return curl_exec($ch);
    
}

function return_date_function($time)
{
    $time=$time/1000;//remove in php 7
            date_default_timezone_set('Africa/Nairobi');//make time kenyan
            //$my_date= date('[d-m-y]-[H-i-s]',$t);
           $my_date= date('d/M/Y-[h:i:s]',$time);
            return $my_date;
}

function bytes_to_megabytes($size)
{
   
            return ($size/1024)/1024;
}


function return_script_order($passed_sort_column,$passed_sort_order,$required_column)
{
    $sort_order="asc";//by default its asc
    $passed_sort_column=trim(strtolower($passed_sort_column));
    $passed_sort_order=trim(strtolower($passed_sort_order));
    $required_column=trim(strtolower($required_column));
    
    if($passed_sort_column==$required_column)//if passed sort column is requested column make sort the opposite
    {
        $sort_order=$passed_sort_order=="asc"?"dsc":"asc";
    }
    //else sort order is ascending
    
    return $sort_order;
}

//function to fetch policy type
function fetch_policy_type($type,$origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyFetchType";

    $myvars='type='.$type;

    $header_array= array('Authorization:'.api_key_is(),'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    return $returned_json_decoded;
}

//function to fetch policy type
function fetch_policy_type_specific($type,$policy_number,$origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyFetchTypeSpecific";

    $myvars='type='.$type.'&policy_number='.$policy_number;

    $header_array= array('Authorization:'.api_key_is(),'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    return $returned_json_decoded;
}

//function to fetch user type
function fetch_policy_user_type($type,$email,$session_key,$cookie,$origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyUserFetchType";

    $myvars='type='.$type.'&email='.$email.'&session_key='.$session_key.'&email='.$email;

    $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$cookie,'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    return $returned_json_decoded;
}


//function to fetch user type specific
function fetch_policy_user_type_specific($policy_id,$session_key,$cookie,$origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicyUserFetchTypeSpecific";

    $myvars='_id='.$policy_id.'&session_key='.$session_key;

    $header_array= array('Authorization:'.api_key_is(),'Cookie:'.$cookie,'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    return $returned_json_decoded;
}


//function make table out of medical array
function make_list_out_of_medical_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.='Min: '.$value['minimum'].' Max: '.$value['maximum'].': ';
            
            $list_options_sub='';
            $count=$value['premium_limit_count'];
            
            for ($index = 0; $index < $count; $index++) 
            {
                $limit_name='limit_'.$index;
                $premium_name='premium_'.$index;
                $options=$value['options'];
                
                $list_options_sub.='(Limit: '.$options[$limit_name].' Premium: '.$options[$premium_name].')';
        
            }
            
            $list_options.=' ['.$list_options_sub.'] ';
    }
    
    return ''.$list_options.'';
}


function make_list_out_of_wiba_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=' [Category name: '.$value['category_name'].', Category multiplier: '.$value['category_multiplier'].'] ';
            
           
    }
    
    return ''.$list_options.'';
}


function make_list_out_of_wiba_employee_liability_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=' [Option name: '.$value['option_name'].', Limit: '.number_format($value['limit'],2).', Multiplier: '.number_format($value['multiplier'],2).'] ';
            
           
    }
    
    return ''.$list_options.'';
}

//function make table out of accident array
function make_list_out_of_accident_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=$value['option_name'].': ['.$value['premium'].'] ';
            
           
    }
    
    return $list_options;
}


//function make table out of maternity array
function make_list_out_of_maternity_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=$value['option_name'].': ['.$value['premium'].'] ';
            
           
    }
    
    return $list_options;
}


//function make table out of dental array
function make_list_out_of_dental_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=$value['option_name'].': ['.$value['premium'].'] ';
            
           
    }
    
    return $list_options;
}

//function make table out of optical array
function make_list_out_of_optical_array($array)
{
    $list_options='';
    foreach ($array as $value) 
    {
        $list_options.=$value['option_name'].': ['.$value['premium'].'] ';
            
           
    }
    
    return $list_options;
}

function make_cart_item_id($type,$count)
{
    $item_id='';
    switch ($type) 
    {
            case 1:
                $seed='Motor insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 2:
                $seed='In patient medical insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 3:
                $seed='Accident insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 4:
                $seed='Contractors all risk insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 5:
                $seed='Performance Bond insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 6:
                $seed='Home insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 7:
                $seed='Home insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 8:
                $seed='Maternity insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 9:
                $seed='Dental insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 10:
                $seed='Optical insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            case 11:
                $seed='Out patient medical insurance'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
            
            
            default:
                $seed='mario is sick of peach and her shit'.time().rand(10, 100000);
                $item_id= md5($seed);
            break;
    }
    
    return $item_id;
    
}

function make_medical_father_radio_buttons($father_array)
{
    $radio_form='';
    
    //echo json_encode($father_array);
    foreach ($father_array as $value) 
    {
        $maximum=$value['maximum'];
        $minimum=$value['minimum'];
        
        $premium_limit_count=$value['premium_limit_count'];
        
        $options=$value['options'];
        
        $radio_button_name='father_'.$minimum.'_'.$maximum;
        
        $radio_form_header='Minimum: '.$minimum.' Maximum: '.$maximum;
        
        $radio_form.='<br>'.$radio_form_header.'<br>';
        
        for ($index = 0; $index < $premium_limit_count; $index++) 
        {
            
            $premium_name='premium_'.$index;
            $limit_name='limit_'.$index;
            
            $premium_value=$options[$premium_name];
            $limit_value=$options[$limit_name];
            
            $radio_button_id=$radio_button_name.'_'.$index;
            
            $radio_form.=' <label class="container"> Limit: KES. '.number_format($limit_value).'&nbsp;&nbsp;  Premium: KES. '.number_format($premium_value).' <input type="radio" name="'.$radio_button_name.'"  id="'.$radio_button_id.'" value="'.$limit_value.'" premium="'.$premium_value.'"  onclick="control_father_current_selected(\''.$radio_button_id.'\');" total="father"/> <span class="checkmark"></span></label><br>';
        }
        
    }
    
    return $radio_form;
}


function make_medical_mother_radio_buttons($mother_array)
{
    $radio_form='';
    
    //echo json_encode($mother_array);
    foreach ($mother_array as $value) 
    {
        $maximum=$value['maximum'];
        $minimum=$value['minimum'];
        
        $premium_limit_count=$value['premium_limit_count'];
        
        $options=$value['options'];
        
        $radio_button_name='mother_'.$minimum.'_'.$maximum;
        
        $radio_form_header='Minimum: '.$minimum.' Maximum: '.$maximum;
        
        $radio_form.='<br>'.$radio_form_header.'<br>';
        
        for ($index = 0; $index < $premium_limit_count; $index++) 
        {
            
            $premium_name='premium_'.$index;
            $limit_name='limit_'.$index;
            
            $premium_value=$options[$premium_name];
            $limit_value=$options[$limit_name];
            
            $radio_button_id=$radio_button_name.'_'.$index;
            
            $radio_form.=' <label class="container"> Limit: KES. '.number_format($limit_value).'&nbsp;&nbsp; Premium: KES. '.number_format($premium_value).' <input type="radio" name="'.$radio_button_name.'"  id="'.$radio_button_id.'" value="'.$limit_value.'" premium="'.$premium_value.'"  onclick="control_mother_current_selected(\''.$radio_button_id.'\');" total="mother"/> <span class="checkmark"></span></label><br>';
        }
        
    }
    
    return $radio_form;
}


function make_medical_children_radio_buttons_with_number_input($children_array)
{
    $radio_form='';
    
    //echo json_encode($children_array);
    foreach ($children_array as $value) 
    {
        $maximum=$value['maximum'];
        $minimum=$value['minimum'];
        
        $premium_limit_count=$value['premium_limit_count'];
        
        $options=$value['options'];
        
        $radio_button_name='children_'.$minimum.'_'.$maximum;
        $input_name='input_children_'.$minimum.'_'.$maximum;
        
        $radio_form_header='Minimum: '.$minimum.' Maximum: '.$maximum;
        
        $radio_form.='<br>'.$radio_form_header.'<br>';
        
        $radio_form.='Number of children: <input type="number" premium="0" class="i_am_children_input" radio_button_name="'.$radio_button_name.'" name="'.$input_name.'" id="'.$input_name.'" total="children"/></br>';
        
        for ($index = 0; $index < $premium_limit_count; $index++) 
        {
            
            $premium_name='premium_'.$index;
            $limit_name='limit_'.$index;
            
            $premium_value=$options[$premium_name];
            $limit_value=$options[$limit_name];
            
            $radio_button_id=$radio_button_name.'_'.$index;
            
            $radio_form.=' <label class="container">Limit: KES. '.number_format($limit_value).' &nbsp;&nbsp; Premium: KES. '.number_format($premium_value).' <input type="radio" name="'.$radio_button_name.'"  id="'.$radio_button_id.'" value="'.$limit_value.'" premium="'.$premium_value.'" onclick="control_children_current_selected(\''.$radio_button_id.'\');"  total="children" /><span class="checkmark"></span></label> <br>';
        }
        
        
    }
    
    return $radio_form;
}


function make_accident_radio_buttons($options,$radio_button_name)
{
    $radio_form='';
    //$radio_button_name='premium_options';   
    //echo json_encode($options);
    foreach ($options as $value) 
    {
        $option_name=$value['option_name'];
        $premium=$value['premium'];
        $html_url=$value['html_url'];
        
        //request html_content
        $html_content=  send_curl_post($html_url, null, null);
        
         
        $radio_form.='<h2><br>'.strtoupper($option_name).'</h2> <label class="container"> Premium: KES. '.number_format($premium).' <input type="radio" name="'.$radio_button_name.'" value="'.$option_name.'" premium="'.$premium.'"/> <span class="checkmark"></span></label> <h4>See what is covered</h4>'.$html_content.'<br><br>';
        
        
    }
    
    return $radio_form;
}


function make_maternity_radio_buttons($options,$radio_button_name)
{
    $radio_form='';
    //$radio_button_name='premium_options';   
    //echo json_encode($options);
    foreach ($options as $value) 
    {
        $option_name=$value['option_name'];
        $premium=$value['premium'];
        $html_url=$value['html_url'];
        
        //request html_content
        $html_content=  send_curl_post($html_url, null, null);
        
         
        $radio_form.='<h2> <br>'.strtoupper($option_name).'</h2> <label class="container">Premium: KES. '.number_format($premium).' <input type="radio" name="'.$radio_button_name.'" value="'.$option_name.'" premium="'.$premium.'"/> <span class="checkmark"></span></label> <h4>See what is covered</h4>'.$html_content.'<br><br>';
        
        
    }
    
    return $radio_form;
}


function make_dental_radio_buttons($options,$radio_button_name)
{
    $radio_form='';
    //$radio_button_name='premium_options';   
    //echo json_encode($options);
    foreach ($options as $value) 
    {
        $option_name=$value['option_name'];
        $premium=$value['premium'];
        $html_url=$value['html_url'];
        
        //request html_content
        $html_content=  send_curl_post($html_url, null, null);
        
         
        $radio_form.='<h2> <br>'.strtoupper($option_name).'</h2> <label class="container"> Premium: KES. '.number_format($premium).' <input type="radio" name="'.$radio_button_name.'" value="'.$option_name.'" premium="'.$premium.'"/><span class="checkmark"></span></label>  <h4>See what is covered</h4>'.$html_content.'<br><br>';
        
        
    }
    
    return $radio_form;
}



function make_optical_radio_buttons($options,$radio_button_name)
{
    $radio_form='';
    //$radio_button_name='premium_options';   
    //echo json_encode($options);
    foreach ($options as $value) 
    {
        $option_name=$value['option_name'];
        $premium=$value['premium'];
        $html_url=$value['html_url'];
        
        //request html_content
        $html_content=  send_curl_post($html_url, null, null);
        
         
        $radio_form.='<h2><br>'.strtoupper($option_name).'</h2> <label class="container">Premium: KES. '.number_format($premium).' <input type="radio" name="'.$radio_button_name.'" value="'.$option_name.'" premium="'.$premium.'"/><span class="checkmark"></span></label>  <h4>See what is covered</h4> '.$html_content.'<br><br>';
        
        
    }
    
    return $radio_form;
}


//make shopping kart for motor
function make_shoping_kart_for_motor($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    $table='';
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $insured_item_value=$value_items['insured_item_value'];
            $excess_protector_percentage_is_boolean=$value_items['excess_protector_percentage_is_boolean'];
            $political_risk_terrorism_percentage_is_boolean=$value_items['political_risk_terrorism_percentage_is_boolean'];
            $aa_membership_is_boolean=$value_items['aa_membership_is_boolean'];
            $company_name=$value_items['company_name'];
            $premium_percentage=$value_items['premium_percentage'];
            $excess_protector_percentage=$value_items['excess_protector_percentage'];
            $political_risk_terrorism_percentage=$value_items['excess_protector_percentage'];
            $aa_membership=$value_items['aa_membership'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
            
        }
        
        
    
    
     $x_is=($premium_percentage/100)*$insured_item_value;
     $y_is=$x_is *(0.45/100);
     $premium=$x_is+$y_is+40;
     
     if($excess_protector_percentage_is_boolean=="true")
     {
         $excess_protector_percentage_is=($excess_protector_percentage/100)*$insured_item_value;
         
     }
     else
     {
         $excess_protector_percentage_is=0;
     }
     
     if($political_risk_terrorism_percentage_is_boolean=="true")
     {
         $political_risk_terrorism_percentage_is=($political_risk_terrorism_percentage/100)*$insured_item_value;
        
     }
     else
     {
         $political_risk_terrorism_percentage_is=0;
     }
     
     if($aa_membership_is_boolean=="true")
     {
         $aa_membership_is_is=$aa_membership;
        
     }
     else
     {
         $aa_membership_is_is=0;
     }
    
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Motor value: KES. '.number_format($insured_item_value).'</h2>';
    $table.='<table>';
    $table.='<tr><th>Premium</th><td>KES. '.number_format($premium).'</td></tr>';
    $table.='<tr><th>Excess protector</th><td>KES. '.number_format($excess_protector_percentage_is).'</td></tr>';
    $table.='<tr><th>Political risk</th><td>KES. '.number_format($political_risk_terrorism_percentage_is).'</td></tr>';
    $table.='<tr><th>AA membership</th><td>KES. '.number_format($aa_membership_is_is).'</td></tr>';
    $table.='<tr><th>TOTAL</th><th>KES. '.number_format($premium+$excess_protector_percentage_is+$political_risk_terrorism_percentage_is+$aa_membership_is_is).'</th></tr>';
    $table.='</table>';
    
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'insured_item_value'=>$insured_item_value,'excess_protector_percentage'=>$excess_protector_percentage_is_boolean,'political_risk_terrorism_percentage'=>$political_risk_terrorism_percentage_is_boolean,'aa_membership'=>$aa_membership_is_boolean);
    
   
}

//make motor view
function make_motor_policy_view($value_items)
{
    
    
    $table='';
    
    
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $insured_item_value=$value_items['insured_item_value'];
            $excess_protector_percentage_is_boolean=$value_items['excess_protector_percentage_is_boolean'];
            $political_risk_terrorism_percentage_is_boolean=$value_items['political_risk_terrorism_percentage_is_boolean'];
            $aa_membership_is_boolean=$value_items['aa_membership_is_boolean'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $company_name=$value_items['company_name'];
            $premium_percentage=$value_items['premium_percentage'];
            $excess_protector_percentage=$value_items['excess_protector_percentage'];
            $political_risk_terrorism_percentage=$value_items['excess_protector_percentage'];
            $aa_membership=$value_items['aa_membership'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
       
        
        
    
    
     $x_is=($premium_percentage/100)*$insured_item_value;
     $y_is=$x_is *(0.45/100);
     $premium=$x_is+$y_is+40;
     
     if($excess_protector_percentage_is_boolean==true)
     {
         $excess_protector_percentage_is=($excess_protector_percentage/100)*$insured_item_value;
         
     }
     else
     {
         $excess_protector_percentage_is=0;
     }
     
     if($political_risk_terrorism_percentage_is_boolean==true)
     {
         $political_risk_terrorism_percentage_is=($political_risk_terrorism_percentage/100)*$insured_item_value;
        
     }
     else
     {
         $political_risk_terrorism_percentage_is=0;
     }
     
     if($aa_membership_is_boolean==true)
     {
         $aa_membership_is_is=$aa_membership;
        
     }
     else
     {
         $aa_membership_is_is=0;
     }
    
    $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
    $total_is=$premium+$excess_protector_percentage_is+$political_risk_terrorism_percentage_is+$aa_membership_is_is;
    
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Motor value: KES. '.number_format($insured_item_value).'</h2>';
    $table.='<table>';
    $table.='<tr><th>Premium</th><td>KES. '.number_format($premium).'</td></tr>';
    $table.='<tr><th>Excess protector</th><td>KES. '.number_format($excess_protector_percentage_is).'</td></tr>';
    $table.='<tr><th>Political risk</th><td>KES. '.number_format($political_risk_terrorism_percentage_is).'</td></tr>';
    $table.='<tr><th>AA membership</th><td>KES. '.number_format($aa_membership_is_is).'</td></tr>';
    $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total_is).'</th></tr>';
    $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
    $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
    $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
    $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
    $table.='</table>';
    
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total_is,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
   
}




//make shopping kart for inpatient
function make_shoping_kart_for_in_patient_medical($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    $table='';
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $fathers_array=$value_items['fathers_array'];
            $mothers_array=$value_items['mothers_array'];
            $childrens_array=$value_items['childrens_array'];
            $company_name=$value_items['company_name'];
            $father_insurance=$value_items['father_insurance'];
            $mother_insurance=$value_items['mother_insurance'];
            $children_insurance=$value_items['children_insurance'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
            
            
        }
        
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Fathers Insurance</h2>';
    
     
    $fathers_total=0;
    
    foreach ($fathers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$father_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $fathers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($fathers_total).'</td></tr></table>';//zero because option is not selected
    
    
    $table.='<h2>Mothers Insurance</h2>';
    
    
    
    $mothers_total=0;
    
    foreach ($mothers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$mother_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $mothers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($mothers_total).'</td></tr></table>';//zero because option is not selected
    
     
     $table.='<h2>Childrens Insurance</h2>';
    
    
    
    $childrens_total=0;
    
    foreach ($childrens_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        $number_of_children=$value['number_of_children'];
        $number_of_children=$number_of_children==''?0:$number_of_children;//if blank make zero
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.' Number of children: '.$number_of_children.' </h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true && $number_of_children >0)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$children_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $childrens_total+=($premium*$number_of_children);
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     $table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($childrens_total+$mothers_total+$fathers_total).'</td></tr></table>';//zero because option is not selected
    
     
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_father_insurance'=>$fathers_array,'selected_mother_insurance'=>$mothers_array,'selected_children_insurance'=>$childrens_array);
    
    
}

//make shopping kart for inpatient
function make_in_patient_medical_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    $table='';
    
    
        
            
            
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $fathers_array=$value_items['fathers_array'];
            $mothers_array=$value_items['mothers_array'];
            $childrens_array=$value_items['childrens_array'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $company_name=$value_items['company_name'];
            $father_insurance=$value_items['father_insurance'];
            $mother_insurance=$value_items['mother_insurance'];
            $children_insurance=$value_items['children_insurance'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
            
       
        
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Fathers Insurance</h2>';
    
     
    $fathers_total=0;
    
    foreach ($fathers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
       
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
             
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
               // echo $boolean.'<br>';
                if($boolean=="true")
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$father_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $fathers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($fathers_total).'</td></tr></table>';//zero because option is not selected
    
    
    $table.='<h2>Mothers Insurance</h2>';
    
    
    
    $mothers_total=0;
    
    foreach ($mothers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean=="true")
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$mother_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $mothers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($mothers_total).'</td></tr></table>';//zero because option is not selected
    
     
     $table.='<h2>Childrens Insurance</h2>';
    
    
    
    $childrens_total=0;
    
    foreach ($childrens_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
        $number_of_children=$value['number_of_children'];
        $number_of_children=$number_of_children==''?0:$number_of_children;//if blank make zero
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.' Number of children: '.$number_of_children.' </h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean=="true" && $number_of_children >0)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$children_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $childrens_total+=($premium*$number_of_children);
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
    $seconds_in_a_day=3600*24;
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    $total_is=$childrens_total+$mothers_total+$fathers_total;
     //total   
        $table.='<table><tr><th>TOTAL</th><th>KES. '.number_format($total_is).'</th></tr>';//zero because option is not selected
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';       
     
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total_is,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
}


//make shopping kart for outpatient
function make_shoping_kart_for_out_patient_medical($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    $table='';
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $fathers_array=$value_items['fathers_array'];
            $mothers_array=$value_items['mothers_array'];
            $childrens_array=$value_items['childrens_array'];
            $company_name=$value_items['company_name'];
            $father_insurance=$value_items['father_insurance'];
            $mother_insurance=$value_items['mother_insurance'];
            $children_insurance=$value_items['children_insurance'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
            
            
        }
        
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Fathers Insurance</h2>';
    
    
    
    $fathers_total=0;
    
    foreach ($fathers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$father_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $fathers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($fathers_total).'</td></tr></table>';//zero because option is not selected
    
    
    $table.='<h2>Mothers Insurance</h2>';
    
    
    
    $mothers_total=0;
    
    foreach ($mothers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$mother_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $mothers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($mothers_total).'</td></tr></table>';//zero because option is not selected
    
     
     $table.='<h2>Childrens Insurance</h2>';
    
    
    
    $childrens_total=0;
    
    foreach ($childrens_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=$value['options'];
        $number_of_children=$value['number_of_children'];
        $number_of_children=$number_of_children==''?0:$number_of_children;//if blank make zero
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.' Number of children: '.$number_of_children.' </h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean==true && $number_of_children >0)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$children_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $childrens_total+=($premium*$number_of_children);
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     $table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($childrens_total+$mothers_total+$fathers_total).'</td></tr></table>';//zero because option is not selected
    
     
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_father_insurance'=>$fathers_array,'selected_mother_insurance'=>$mothers_array,'selected_children_insurance'=>$childrens_array);
    
    
}


//make shopping kart for out patient
function make_out_patient_medical_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    $table='';
    
    
        
            
            
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $fathers_array=$value_items['fathers_array'];
            $mothers_array=$value_items['mothers_array'];
            $childrens_array=$value_items['childrens_array'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $company_name=$value_items['company_name'];
            $father_insurance=$value_items['father_insurance'];
            $mother_insurance=$value_items['mother_insurance'];
            $children_insurance=$value_items['children_insurance'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
            
       
        
    $table.='<h3>'.$company_name.'</h3>';
    $table.='<h2>Fathers Insurance</h2>';
    
     
    $fathers_total=0;
    
    foreach ($fathers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
       
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
             
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
               // echo $boolean.'<br>';
                if($boolean=="true")
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$father_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $fathers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($fathers_total).'</td></tr></table>';//zero because option is not selected
    
    
    $table.='<h2>Mothers Insurance</h2>';
    
    
    
    $mothers_total=0;
    
    foreach ($mothers_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.'</h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean=="true")
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$mother_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $mothers_total+=$premium;
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
     //total   
     //$table.='<table><tr><th>TOTAL</th><td>KES. '.number_format($mothers_total).'</td></tr></table>';//zero because option is not selected
    
     
     $table.='<h2>Childrens Insurance</h2>';
    
    
    
    $childrens_total=0;
    
    foreach ($childrens_array as $key => $value) 
    {
        
        $minimum=$value['minimum'];
        $maximum=$value['maximum'];
        $options=array($value['options']);
        $number_of_children=$value['number_of_children'];
        $number_of_children=$number_of_children==''?0:$number_of_children;//if blank make zero
        
        $table.='<h4>Minimum: '.$minimum.' Maximum: '.$maximum.' Number of children: '.$number_of_children.' </h4>';
        $table.='<table><tr><th>Limit</th><th>Premium</th></tr>';
        //echo json_encode($options);
        foreach ($options as $value_limit_boolean) 
        {
            //$limit=$key_limit_boolean;//limit
            $boolean_array=$value_limit_boolean;
            
            foreach ($boolean_array as $limit => $boolean) 
            {
                if($boolean=="true" && $number_of_children >0)
                {
                    $premium=get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$children_insurance);
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format($premium).'</td></tr>';
                    $childrens_total+=($premium*$number_of_children);
                }
                else
                {
                    $table.='<tr><td>KES. '.number_format($limit).'</td><td>KES. '.number_format(0).'</td></tr>';//zero because option is not selected
                }
            }
            
        }
       $table.='</table>';
    }
    
    $seconds_in_a_day=3600*24;
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
    $total_is=$childrens_total+$mothers_total+$fathers_total;
     //total   
        $table.='<table><tr><th>TOTAL</th><th>KES. '.number_format($total_is).'</th></tr>';//zero because option is not selected
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';       
     
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total_is,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
    
}


//function to get corresponding premium with max and min and limit from medical option arra
function get_premium_from_medical_option_limit_max_min($minimum,$maximum,$limit,$option_insurance)
{
    $premium_is=0;
    
    //echo json_encode($option_insurance);
    foreach ($option_insurance as $value) 
    {
        $maximum=$value['maximum'];
        $minimum=$value['minimum'];
        
        $premium_limit_count=$value['premium_limit_count'];
        
        $options=$value['options'];
        
        
        
        for ($index = 0; $index < $premium_limit_count; $index++) 
        {
            
            $premium_name='premium_'.$index;
            $limit_name='limit_'.$index;
            
            $premium_value=$options[$premium_name];
            $limit_value=$options[$limit_name];
            
            $premium_is=$limit_value==$limit? $premium_value:$premium_is;//assing premium if limit matches
        }
        
    }
    
    return $premium_is;
}


//make shopping kart for maternity
function make_shoping_kart_for_maternity_medical($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_options'=>$selected_options);
    
}


//make shopping kart for dental
function make_shoping_kart_for_dental_medical($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_options'=>$selected_options);
    
}

//make shopping kart for optical
function make_shoping_kart_for_optical_medical($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_options'=>$selected_options);
    
}

//get premium using option name from maternity,optical,dental options array
function get_premium_using_option_name_from_maternity_optical_dental_array($option_name_is,$options_array)
{
    $premium_is=0;
            foreach ($options_array as $value) 
            {
                $option_name=$value['option_name'];
                $premium=$value['premium'];
                //$html_url=$value['html_url'];
                
                if($option_name==$option_name_is)
                {
                    $premium_is=$premium;
                }
                
            }
    return $premium_is;
}


//make shopping kart for accident
function make_shoping_kart_for_accident($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=get_premium_using_option_name_from_accident_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'selected_options'=>$selected_options);
    
}


//make view for accident
function make_accident_policy_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
       
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=get_premium_using_option_name_from_accident_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
            
      $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
}


//make view for maternity
function make_maternity_policy_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
       
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=  get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
            
      $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
}


//make view for dental
function make_dental_policy_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
       
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=  get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
            
      $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
}


//make view for optical
function make_optical_policy_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
       
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $options=$value_items['options'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $selected_options=$value_items['selected_options'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
        
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<h2>Selected options</h2>';
            $table.='<table>';
            $table.='<tr><th>Option name</th><th>Premium</th></tr>';
            
            //loop through selected options
            foreach ($selected_options as $value) 
            {
                foreach ($value as $key => $boolean_values) 
                {
                    
                    if($boolean_values==true)//if selected
                    {
                         $premium=  get_premium_using_option_name_from_maternity_optical_dental_array($key,$options);
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format($premium).'</td></tr>';
                         $total+=$premium;
                        //echo $key;
                    }
                    else//if not selected
                    {
                         $table.='<tr><td>'.$key.'</td><td>KES. '.number_format(0).'</td></tr>';
                    }
                }
            }
            
      $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;        
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
    
}

//get premium using option name from maternity,optical,dental options array
function get_premium_using_option_name_from_accident_array($option_name_is,$options_array)
{
    $premium_is=0;
            foreach ($options_array as $value) 
            {
                $option_name=$value['option_name'];
                $premium=$value['premium'];
                //$html_url=$value['html_url'];
                
                if($option_name==$option_name_is)
                {
                    $premium_is=$premium;
                }
                
            }
    return $premium_is;
}



//make shopping kart for contrcators all risk
function make_shoping_kart_for_contractors_all_risk($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $contract_price_multiplier=$value_items['contract_price_multiplier'];
            $contract_price=$value_items['contract_price'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Contract price</th><th>Premium</th></tr>';
            
             $w_is=$contract_price_multiplier*$contract_price;
             $x_is=$w_is *0.0045;
             $premium=$x_is+$w_is+40;
             
     $table.='<tr><td>KES. '.number_format($contract_price).'</td><td>KES. '.number_format($premium).'</td></tr>';       
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($premium).'</td></tr>';
     $table.='</table>';
     
     return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'contract_price'=>$contract_price);
       
    
}

//make view  for contrcators all risk
function make_contractors_all_risk_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
        
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $contract_price_multiplier=$value_items['contract_price_multiplier'];
            $contract_price=$value_items['contract_price'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
       
             
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Contract price</th><th>Premium</th></tr>';
            
             $w_is=$contract_price_multiplier*$contract_price;
             $x_is=$w_is *0.0045;
             $premium=$x_is+$w_is+40;
    
              $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><td>KES. '.number_format($contract_price).'</td><td>KES. '.number_format($premium).'</td></tr>';       
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($premium).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
     return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$premium,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
       
    
}


//make shopping kart for contrcators all risk
function make_shoping_kart_for_performance_bond_insurance($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            $contract_price_multiplier=$value_items['contract_price_multiplier'];
            $w_multiplier=$value_items['w_multiplier'];
            $contract_price=$value_items['contract_price'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Contract price</th><th>Premium</th></tr>';
            
                $w_is=$contract_price_multiplier*$contract_price;
                $x_is=$w_is * $w_multiplier;
                $y_is=$x_is *0.0045;
                $premium=$x_is+$w_is+$y_is+40;
                $total=$premium;
     $table.='<tr><td>KES. '.number_format($contract_price).'</td><td>KES. '.number_format($premium).'</td></tr>';       
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'contract_price'=>$contract_price);
        
    
}

//make view for contrcators all risk
function make_performance_bond_insurance_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
        
            $item_id=$key;//item id
            
            $policy_id=$value_items['policy_id'];
            $policy_number=$value_items['policy_number'];
            $contract_price_multiplier=$value_items['contract_price_multiplier'];
            $w_multiplier=$value_items['w_multiplier'];
            $contract_price=$value_items['contract_price'];
            $active_status=$value_items['active_status'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Contract price</th><th>Premium</th></tr>';
            
                $w_is=$contract_price_multiplier*$contract_price;
                $x_is=$w_is * $w_multiplier;
                $y_is=$x_is *0.0045;
                $premium=$x_is+$w_is+$y_is+40;
                $total=$premium;
                
                $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><td>KES. '.number_format($contract_price).'</td><td>KES. '.number_format($premium).'</td></tr>';       
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
        
    
}

function make_shoping_kart_for_fire_burglary_theft_insurance($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            
            $fire_price=$value_items['fire_price'];
            $burglary_price=$value_items['burglary_price'];
            $all_risk_price=$value_items['all_risk_price'];
            
            $fire_multiplier=$value_items['fire_multiplier'];
            $fire_html_url=$value_items['fire_html_url'];
            $burglary_multiplier=$value_items['burglary_multiplier'];
            $burglary_html_url=$value_items['burglary_html_url'];
            $all_risk_multiplier=$value_items['all_risk_multiplier'];
            $all_risk_html_url=$value_items['all_risk_html_url'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Price type</th><th>Premium</th></tr>';
            
                $w_is=$fire_multiplier*$fire_price;
                $x_is=$w_is *0.0045;
                $fire_premium=$x_is+$w_is+40;
                
                $fire_premium=$fire_price==0? 0 : $fire_premium;//adjust for zero
                
                $w_is=$burglary_multiplier*$burglary_price;
                $x_is=$w_is *0.0045;
                $burglary_premium=$x_is+$w_is+40;
                
                $burglary_premium=$burglary_price==0? 0 : $burglary_premium;//adjust for zero
                
                $w_is=$all_risk_multiplier*$all_risk_price;
                $x_is=$w_is *0.0045;
                $all_risk_premium=$x_is+$w_is+40;
                
                $all_risk_premium=$all_risk_price==0? 0 : $all_risk_premium;//adjust for zero
                
     $table.='<tr><td>Fire KES. '.number_format($fire_price).'</td><td>KES. '.number_format($fire_premium).'</td></tr>'; 
     $table.='<tr><td>Burglary KES. '.number_format($burglary_price).'</td><td>KES. '.number_format($burglary_premium).'</td></tr>'; 
     $table.='<tr><td>All risk KES. '.number_format($all_risk_price).'</td><td>KES. '.number_format($all_risk_premium).'</td></tr>'; 
     
     
     $total=$fire_premium+$burglary_premium+$all_risk_premium;
     
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
    return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'fire_price'=>$fire_price,'burglary_price'=>$burglary_price,'all_risk_price'=>$all_risk_price);
        
    
}

//make fire burglary view
function make_fire_burglary_theft_insurance_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
    
       
        
            $policy_number=$value_items['policy_number'];
             $policy_id=$value_items['policy_id'];
             
            $fire_price=$value_items['fire_price'];
            $burglary_price=$value_items['burglary_price'];
            $all_risk_price=$value_items['all_risk_price'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            
             $active_status=$value_items['active_status'];
            $fire_multiplier=$value_items['fire_multiplier'];
            $fire_html_url=$value_items['fire_html_url'];
            $burglary_multiplier=$value_items['burglary_multiplier'];
            $burglary_html_url=$value_items['burglary_html_url'];
            $all_risk_multiplier=$value_items['all_risk_multiplier'];
            $all_risk_html_url=$value_items['all_risk_html_url'];
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Price type</th><th>Premium</th></tr>';
            
                $w_is=$fire_multiplier*$fire_price;
                $x_is=$w_is *0.0045;
                $fire_premium=$x_is+$w_is+40;
                
                $fire_premium=$fire_price==0? 0 : $fire_premium;//adjust for zero
                
                $w_is=$burglary_multiplier*$burglary_price;
                $x_is=$w_is *0.0045;
                $burglary_premium=$x_is+$w_is+40;
                
                $burglary_premium=$burglary_price==0? 0 : $burglary_premium;//adjust for zero
                
                $w_is=$all_risk_multiplier*$all_risk_price;
                $x_is=$w_is *0.0045;
                $all_risk_premium=$x_is+$w_is+40;
                
                $all_risk_premium=$all_risk_price==0? 0 : $all_risk_premium;//adjust for zero
                
     $table.='<tr><td>Fire KES. '.number_format($fire_price).'</td><td>KES. '.number_format($fire_premium).'</td></tr>'; 
     $table.='<tr><td>Burglary KES. '.number_format($burglary_price).'</td><td>KES. '.number_format($burglary_premium).'</td></tr>'; 
     $table.='<tr><td>All risk KES. '.number_format($all_risk_price).'</td><td>KES. '.number_format($all_risk_premium).'</td></tr>'; 
     
     $total=$fire_premium+$burglary_premium+$all_risk_premium;
     
    $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
    return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
        
    
}

//kart for home insurance
function make_shoping_kart_for_home_insurance($kart_item_array)
{
    //echo json_encode($kart_item_array);
    
    
    
        foreach ($kart_item_array as $key => $value_items) 
        {
            $item_id=$key;//item id
        
            $policy_number=$value_items['policy_number'];
            
            $building_value=$value_items['building_value'];
            $content_value=$value_items['content_value'];
            $electronics_value=$value_items['electronics_value'];
            
            $building_multiplier=$value_items['building_multiplier'];
            $content_multiplier=$value_items['content_multiplier'];
            $electronics_multiplier=$value_items['electronics_multiplier'];
            
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $time_stamp=$value_items['time_stamp'];
        }
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Value type</th><th>Premium</th></tr>';
            
                $a_is=$building_multiplier*$building_value;
                $b_is=$a_is *0.0045;
                $building_premium=$a_is+$b_is+40;
                
                $building_premium=$building_value==0? 0 : $building_premium;//adjust for zero
                
                
                $a_is=$content_multiplier*$content_value;
                $b_is=$a_is *0.0045;
                $content_premium=$a_is+$b_is+40;
                
                $content_premium=$content_value==0? 0 : $content_premium;//adjust for zero
                
                $a_is=$electronics_multiplier*$electronics_value;
                $b_is=$a_is *0.0045;
                $electronics_premium=$a_is+$b_is+40;
                
                $electronics_premium=$electronics_value==0? 0 : $electronics_premium;//adjust for zero
                
     $table.='<tr><td>Building KES. '.number_format($building_value).'</td><td>KES. '.number_format($building_premium).'</td></tr>'; 
     $table.='<tr><td>Content KES. '.number_format($content_value).'</td><td>KES. '.number_format($content_premium).'</td></tr>'; 
     $table.='<tr><td>Electronics KES. '.number_format($electronics_value).'</td><td>KES. '.number_format($electronics_premium).'</td></tr>'; 
     
     $total=$building_premium+$content_premium+$electronics_premium;
             
     $table.='<tr><th>TOTAL</th><td>KES. '.number_format($total).'</td></tr>';
     $table.='</table>';
     
   return array('html'=>$table,'item_id'=>$item_id,'policy_number'=>$policy_number,'building_value'=>$building_value,'content_value'=>$content_value,'electronics_value'=>$electronics_value);
        
    
}


//view home insurance
function make_home_insurance_view($value_items)
{
    //echo json_encode($kart_item_array);
    
    
            $policy_number=$value_items['policy_number'];
            $policy_id=$value_items['policy_id'];
            
            $building_value=$value_items['building_value'];
            $content_value=$value_items['content_value'];
            $electronics_value=$value_items['electronics_value'];
            $selected_policy_time_stamp=$value_items['selected_policy_time_stamp'];
            $active_status=$value_items['active_status'];
            $building_multiplier=$value_items['building_multiplier'];
            $content_multiplier=$value_items['content_multiplier'];
            $electronics_multiplier=$value_items['electronics_multiplier'];
            
            $company_name=$value_items['company_name'];
            $expiry_duration_days=$value_items['expiry_duration_days'];
            $logo_url=$value_items['logo_url'];
            $html_url=$value_items['html_url'];
            $company_time_stamp=$value_items['company_time_stamp'];
            
            
            
            
            
            $table='';
    
            $total=0;
    
            $table.='<h3>'.$company_name.'</h3>';
            $table.='<table>';
            $table.='<tr><th>Value type</th><th>Premium</th></tr>';
            
                $a_is=$building_multiplier*$building_value;
                $b_is=$a_is *0.0045;
                $building_premium=$a_is+$b_is+40;
                
                $building_premium=$building_value==0? 0 : $building_premium;//adjust for zero
                
                
                $a_is=$content_multiplier*$content_value;
                $b_is=$a_is *0.0045;
                $content_premium=$a_is+$b_is+40;
                
                $content_premium=$content_value==0? 0 : $content_premium;//adjust for zero
                
                $a_is=$electronics_multiplier*$electronics_value;
                $b_is=$a_is *0.0045;
                $electronics_premium=$a_is+$b_is+40;
                
                $electronics_premium=$electronics_value==0? 0 : $electronics_premium;//adjust for zero
                
     $table.='<tr><td>Building KES. '.number_format($building_value).'</td><td>KES. '.number_format($building_premium).'</td></tr>'; 
     $table.='<tr><td>Content KES. '.number_format($content_value).'</td><td>KES. '.number_format($content_premium).'</td></tr>'; 
     $table.='<tr><td>Electronics KES. '.number_format($electronics_value).'</td><td>KES. '.number_format($electronics_premium).'</td></tr>'; 
      
     
     $total=$building_premium+$content_premium+$electronics_premium;
         
     $seconds_in_a_day=3600*24; 
   
    $seconds_since_policy_was_selected=time()-($selected_policy_time_stamp/1000);
    $days_since_policy_was_selected=(int)($seconds_since_policy_was_selected/$seconds_in_a_day);
    $diff=$expiry_duration_days-$days_since_policy_was_selected;
    
    $active_status_is=$active_status==true? 'Active': 'Inactive';
    
        $table.='<tr><th>TOTAL</th><th>KES. '.number_format($total).'</th></tr>';
        $table.='<tr><th>Policy number</th><td>'.$policy_number.'</td></tr>';
        $table.='<tr><th>Policy date</th><td>'.  return_date_function($selected_policy_time_stamp).'</td></tr>';
        $table.='<tr><th>Expires in</th><td>'.$diff.' day(s)</td></tr>';
        $table.='<tr><th>Status</th><th>'.$active_status_is.'</th></tr>';
        $table.='</table>';
     
   return array('html'=>$table,'status'=>strtolower($active_status_is),'total'=>$total,'policy_id'=>$policy_id,'policy_number'=>$policy_number,'company_name'=>$company_name,'policy_date'=>$selected_policy_time_stamp,'expiry_duration_days'=>$expiry_duration_days);
    
        
    
}

function check_out_kart_with_email($shopping_kart,$email,$source,$full_names=null)
{
    $header_array= array('Authorization:'.api_key_is(),$source);//make header
    
    foreach ($shopping_kart as $key => $value) 
    {//start of foreach ($shopping_kart as $value) 
        $type=$key;
        $kart_item_array=$value;
        switch ($type) 
        {//start of switch $type
                case 1://Motor insurance
                    
                    
                    foreach ($kart_item_array as $value) 
                    { 
                   
                        $item=make_shoping_kart_for_motor($value);
                            
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectMotorInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&insured_item_value='.$item['insured_item_value'].'&excess_protector_percentage='.$item['excess_protector_percentage'].'&political_risk_terrorism_percentage='.$item['political_risk_terrorism_percentage'].'&aa_membership='.$item['aa_membership'];
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                            foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected motor insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected motor insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                           
                            

                            //$returned_json_decoded= json_decode($returned_json,true);//decode

                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        
                    }
                    
                break;
                
                case 2://In patient medical insurance
                    
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_in_patient_medical($value);
                        
                         $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectMedicalInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_father_insurance='.json_encode($item['selected_father_insurance']).'&selected_mother_insurance='.json_encode($item['selected_mother_insurance']).'&selected_children_insurance='.json_encode($item['selected_children_insurance']);
                            
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                              foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected in patient insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected in patient insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                    }

                break;
                
                case 3://Accident insurance
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_accident($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectAccidentInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_options='.json_encode($item['selected_options']);
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                              foreach ($value as $kart_value => $actual_item){}
                            
                           if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected accident insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected accident insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        }

                break;
            
                case 4://Contractors all risk insurance

                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_contractors_all_risk($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectContractorsAllRiskInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&contract_price='.$item['contract_price'];
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                            foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected contractors all risk insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected contractors all risk insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                     
                        }
                break;
            
                case 5://Performance Bond insurance
                        
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_performance_bond_insurance($value);
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectPerfomanceBondInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&contract_price='.$item['contract_price'];
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                           
                             foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected performance bond insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected performance bond insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                     
                        }

                break;
            
                case 6://Fire burglary theft insurance
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_fire_burglary_theft_insurance($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectFireBurglaryTheftInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&fire_price='.$item['fire_price'].'&burglary_price='.$item['burglary_price'].'&all_risk_price='.$item['all_risk_price'];
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                           
                             foreach ($value as $kart_value => $actual_item){}
                            
                           if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected fire burglary insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected fire burglary insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        }

                break;
            
                case 7://Home insurance

                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_home_insurance($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectHomeInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&building_value='.$item['building_value'].'&content_value='.$item['content_value'].'&electronics_value='.$item['electronics_value'];
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                           
                             foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected home insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected home insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        }
                break;
                
                case 8://Maternity insurance
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_maternity_medical($value);
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectMaternityInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_options='.json_encode($item['selected_options']);
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                              foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected maternity insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected maternity insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                    }

                break;
            
                case 9://Dental insurance 
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_dental_medical($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectDentalInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_options='.json_encode($item['selected_options']);
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                             foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected dental insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected dental insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                             
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        }

                break;
            
                case 10://Optical insurance 
                        foreach ($kart_item_array as $value) 
                        {
                            $item=make_shoping_kart_for_optical_medical($value);
                             $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectOpticalInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_options='.json_encode($item['selected_options']);
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                              foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected optical insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected optical insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                        }

                break;
            
                case 11://out patient insurance 
                    foreach ($kart_item_array as $value) 
                    {
                        $item=make_shoping_kart_for_out_patient_medical($value);
                        
                            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.PolicySelectOutMedicalInsurance";
                            
                            $myvars='email='.$email.'&policy_number='.$item['policy_number'].'&selected_father_insurance='.json_encode($item['selected_father_insurance']).'&selected_mother_insurance='.json_encode($item['selected_mother_insurance']).'&selected_children_insurance='.json_encode($item['selected_children_insurance']);
                            
                            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                            
                              foreach ($value as $kart_value => $actual_item){}
                            
                            if(!is_null($full_names) )
                            {
                                    $message_send="Client ".$full_names.": ".$email.", selected out patient insurance policy ".$actual_item['policy_number']." insured at ".$actual_item['company_name']." on ".return_date_function((time()*1000) );
                                    $header_email_is=$full_names.": ".$email." new selected out patient insurance policy ".return_date_function((time()*1000));
                                    send_email_free_message(emai_address_for_admin(),$header_email_is,$message_send,'/le_functions/functions.php'); 

                            }
                            //$returned_json_decoded= json_decode($returned_json,true);//decode
                            
                           
                            //$check_is=$returned_json_decoded["check"];//check

                            //$message_is=$returned_json_decoded["message"];//message
                    }

                break;
            
                default:
                break;
        }//end of switch $type
    }//$shopping_kart as $key => $value
    
}



//make medica array for edit
function make_medical_array_edit($selected_array,$children_check)
{
            $return_array=array();
            $count=0;
            
            //submit father
            //echo json_encode($selected_array);
            foreach ($selected_array as $value) 
            {

                $maximum=$value['maximum'];
                $minimum=$value['minimum'];
                $options=$value['options'];
                
                if($children_check==true)
                {
                    $number_of_children=$value['number_of_children'];
                }
                
                $array=array();//
                foreach ($options as $key => $value) 
                {
                    
                    $array[$key]=$value=="true"?true:false;
                    
                }
                

                $return_array[$count]['minimum']=(int)$minimum;
                $return_array[$count]['maximum']=(int)$maximum;
                
                if($children_check==true)
                {
                    $return_array[$count]['number_of_children']=(int)$number_of_children;
                }
                
                $return_array[$count]['options']=array($array);
                $count++;//increment
            }
            
            return $return_array; 
}



//function to get balance
function get_balance_for_polices($policy_total,$total_payments,$policy_date,$expiry_duration_days)
{
    
     $seconds_in_a_day=3600*24;
    //find passed durations
    $diff_in_seconds=time()-($policy_date/1000);
    
    //turn into days
    $diff_in_days=(int)($diff_in_seconds/$seconds_in_a_day);
    $diff_in_days=$diff_in_days==0?1:$diff_in_days;//fix zero
    
    
    $multiple=$diff_in_days/$expiry_duration_days;
    
    $multiple=(int)$multiple;//round down
    
    $multiple=$multiple==0?1: $multiple;//make 0 a 1
    
    $total_due=$policy_total*$multiple;
    
    $balance=$total_due-$total_payments;
    
    return array('balance'=>$balance,'policy_age_days'=>(int)$diff_in_days,'policy_age_years'=>$multiple);
    
}

//function fetch personal details
function fetch_personal_details($level,$session_key,$cookie,$source)
{
        $personal_info=array();
    
        
       
        $myvars='session_key='.$session_key;
        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$source);
       
        
        
    switch ($level) 
    {
            case 1:
            case 2://admin and junior admin
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchDetails";
                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                        $returned_json_decoded= json_decode($returned_json,true);//decode
                        $check_is=$returned_json_decoded["check"];//check
                        if($check_is==true)//if check is true
                        {  
                            $personal_info["full_names"]=$returned_json_decoded["full_names"];
                            $personal_info["email_address"]=$returned_json_decoded["email_address"];
                            $personal_info["phone_number"]=$returned_json_decoded["phone_number"];
                            $personal_info["national_id"]=$returned_json_decoded["national_id"];
                        }
            break;
        
            case 3://customer
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.ClientFetchDetails";
                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
                        $returned_json_decoded= json_decode($returned_json,true);//decode
                        $check_is=$returned_json_decoded["check"];//check
                        if($check_is==true)//if check is true
                        {  
                            $personal_info["full_names"]=$returned_json_decoded["full_names"];
                            $personal_info["email_address"]=$returned_json_decoded["email_address"];
                            $personal_info["phone_number"]=$returned_json_decoded["phone_number"];
                            $personal_info["national_id"]=$returned_json_decoded["national_id"];
                        }
            break;

            default:
            break;
    }
    
    return $personal_info;
}



//function make_message_list_box
function make_message_drop_down_box($exclude_email_address,$session_key,$cookie,$source,$is_admin)
{
    $options='';
    
    $column=1;//start with super admin
    $limit=99999;//max possible
    $skip=0;//no skip
    $sort_column='full_names';
    $sort_order='dsc';
    
     //fetch senior admin
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchJuniorAdministratorsDetails";

    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;

    $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$source);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    $unique_separator=  return_unique_separator_email_name();
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    //junior admins
    $column=2;//junior admin
     //fetch junior admin
    
    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;
    
    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    
    //claim handlers
    $column=3;
    
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchClaimHandlerDetails";
    
    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;
    
    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    //assessors
    $column=4;
    
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchAssessorLossAdjusterDetails";
    
    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;
    
    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    //repair garages
    $column=5;
    
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchRepairGarageDetails";
    
    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;
    
    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    //towing 
    $column=6;
    
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchTowingAndRescueDetails";
    
    $myvars='session_key='.$session_key.'&column='.$column.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;
    
    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode

    $check_is=$returned_json_decoded["check"];//check
    $message_is=$returned_json_decoded["message"];//check
    
    if($check_is==true)
    {
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                 // $national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                    if($email_address!=$exclude_email_address)//if email is not of the requester
                    {
                         $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                    }
            }//end of foreach $message_is as $value
    }
    
    if($is_admin==true)//if request is from admin the fetch clients coz only admins can message clients
    {
         $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchClientDetails";
         $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check
                        $message_is=$returned_json_decoded["message"];//check

                        if($check_is==true)
                        {
                                foreach ($message_is as $value) 
                                {//start of foreach $message_is as $value
                                      $full_names=$value['full_names'];
                                      $email_address=$value['email_address'];
                                      //$phone_number=$value['phone_number'];
                                     // $national_id=$value['national_id'];
                                      //$time_stamp=$value['time_stamp'];

                                        if($email_address!=$exclude_email_address)//if email is not of the requester
                                        {
                                             $options.='<option value="'.$email_address.'_'.$unique_separator.'_'.$full_names.'"  >'.strtoupper($full_names).'</option>';
                                        }
                                }//end of foreach $message_is as $value
                        }
    }
    $select='<select name="messages_recipient_list" id="messages_recipient_list" >
             <option value="0"  >Choose recipient</option>   
                             '.$options.'
                        </select>';
    
    return $select;
}


function limit_message_content_on_view($content)
{
    //str_split($string)
    $ex=  str_split( $content);
    $limit=17;
    $text_limited='';
    if(count($ex)>=$limit)
    {
        
        for ($index = 0; $index < $limit; $index++) 
        { //echo 'lol';
            $text_limited.=$ex[$index];
        }
        $text_limited.='...';
    }
    else
    {
       $text_limited=$content; 
    }
    
    return $text_limited;
}


//fetch inbox count
function get_inbox_count_function($session_key,$cookie,$source)
{
    
    
    $total=0;
    //fetch messages
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchMessages";

        $myvars='session_key='.$session_key;

        $header_array= array('Authorization:'.api_key_is(),'Origin:'.$source,'Cookie:'.$cookie);

         $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

         $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message

        if($check_is==true)//if check is true
        {

                                        foreach ($message_is as $value) 
                                        {//start of foreach $message_is as $value

                                            //$header=$value['header'];
                                           // $content=$value['content'];
                                            //$time_stamp=$value['time_stamp'];
                                            //$recipient_email=$value['recipient_email'];
                                            //$recipient_name=$value['recipient_name'];
                                            //$total_messages=$value['total_messages'];
                                            $unread_messages=$value['unread_messages'];

                                            $total+=$unread_messages;
                                           
                                     }//end of foreach $message_is as $value


        }
        
        $return_total=$total==0? '': '<span id="messages_total_span">('.$total.')</span>';
        
        return $return_total;
        

}


//fetch mobile payments count
function get_mobile_payments_count_function($session_key,$cookie,$source)
{
    
    
    $total=0;
    $limit=100;//fetch max
    $skip=0;
    $sort_column='seen_status';
    $sort_order='asc';
   
       //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchMobilePayments";

        $myvars='session_key='.$session_key.'&limit='.$limit.'&skip='.$skip.'&sort_column='.$sort_column.'&sort_order='.$sort_order;

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$source);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         $message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        {
            
           
           
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  //$_id=$value['_id']['$oid'];  
                  //$mode_of_payment=$value['mode_of_payment'];
                  //$amount_paid=$value['amount_paid'];
                  //$particulars=$value['particulars'];
                  //$time_date_of_payment=$value['time_date_of_payment'];
                  //$transaction_code=$value['transaction_code'];
                 // $msidn=$value['msidn'];
                  $seen_status=$value['seen_status'];
                  
                  $seen_status_is=$seen_status==0? $total++: $total;
                  
                 
            }//end of foreach $message_is as $value
            
           
        }
       
        $return_total=$total==0? '': '<span id="mobile_payments_total_span">('.$total.')</span>';
        
        return $return_total;
        

}


//fetch claims count
function get_claims_count_function($session_key,$cookie,$source)
{
    
    
    $total=0;
    $limit=100;//fetch max
    $skip=0;
    
     $personal_details_array=fetch_personal_details(3,$session_key,$cookie,$source);
     $email_address=$personal_details_array['email_address'];
   
       //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaims";

        $myvars='session_key='.$session_key.'&limit='.$limit.'&skip='.$skip.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$source);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         $message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        {
            
           
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  //$_id=$value['_id']['$oid'];
                  //$type_of_claim=$value['type_of_claim'];
                  //$claim_number=$value['claim_number'];
                  //$date_reported=$value['date_reported'];
                  //$company_name=$value['company_name'];
                 // $status=$value['status'];
                  $seen_status=$value['seen_status'];
                  
                   $seen_status_is=$seen_status==0? $total++: $total;
                  
            }//end of foreach $message_is as $value
            
            
        }
       
       
        $return_total=$total==0? '': '<span id="claims_total_span">('.$total.')</span>';
        
        return $return_total;
        

}



function sort_files_accordingly($sort_by,$multi_demsional_array)
	{//start of function my_sort_schools_array_function($multi_demsional_array)
	 
	 $size=count($multi_demsional_array);//getting the size of the multi demnsional array to sort	
	 //$answer=$sort_by;
	 
	 switch ($sort_by) 
	 {//start of switch case to sort school items
	 
	 
                   	 
                case 'name'://start of school index case

                                        $my_school_sort_school_index=array();//declaration of sorting array

                                        for($count=0;$count<$size;$count++)//loop to pick index items and place them in a sorting array
                                        {
                                               $my_school_sort_school_index[$count]=$multi_demsional_array[$count]['name'];//moving through each array item and picking index item i.e. [0]
                                        }



                                       if(empty($_SESSION['my_school_sort_school_index']))//session is set but empty initally
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_DESC,SORT_STRING);//sort by descending order
                                               $_SESSION['my_school_sort_school_index']=1;//then set session for next time so as to sort ascending
                                       }
                                       elseif($_SESSION['my_school_sort_school_index']==1)//now session is 1
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_ASC,SORT_STRING);//sort ascending
                                               unset($_SESSION['my_school_sort_school_index']);//unset session so next time its descending
                                       }


                                        //loop to check where each item belongs and create multidimensional array

                                        for($count1=0;$count1<$size;$count1++)//outer loop to look through the sorted array items
                                        {

                                                                for($count2=0;$count2<$size;$count2++)//inner loop to look through multidemsional items to compare
                                                                {
                                                                          if($my_school_sort_school_index[$count1]==$multi_demsional_array[$count2]['name'])//condition to compare if sorted array item is equal to that of a inner loop count2
                                                                          {
                                                                               //$my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];

                                                                                     if(!empty($my_new_multi_demnsional_array[$count1]) )
                                                                                          {
                                                                                             $my_new_multi_demnsional_array[$count1+1]=$multi_demsional_array[$count2];
                                                                                                 $count1++;
                                                                                          }
                                                                                          else 
                                                                                          {
                                                                                                $my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];  
                                                                                          }

                                                                          }

                                                                }

                                        }

                        break;//end of school index case
			
                case 'last_modified'://start of school index case

                                        $my_school_sort_school_index=array();//declaration of sorting array

                                        for($count=0;$count<$size;$count++)//loop to pick index items and place them in a sorting array
                                        {
                                               $my_school_sort_school_index[$count]=$multi_demsional_array[$count]['last_modified'];//moving through each array item and picking index item i.e. [0]
                                        }



                                       if(empty($_SESSION['my_school_sort_school_index']))//session is set but empty initally
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_DESC,SORT_STRING);//sort by descending order
                                               $_SESSION['my_school_sort_school_index']=1;//then set session for next time so as to sort ascending
                                       }
                                       elseif($_SESSION['my_school_sort_school_index']==1)//now session is 1
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_ASC,SORT_STRING);//sort ascending
                                               unset($_SESSION['my_school_sort_school_index']);//unset session so next time its descending
                                       }


                                        //loop to check where each item belongs and create multidimensional array

                                        for($count1=0;$count1<$size;$count1++)//outer loop to look through the sorted array items
                                        {

                                                                for($count2=0;$count2<$size;$count2++)//inner loop to look through multidemsional items to compare
                                                                {
                                                                          if($my_school_sort_school_index[$count1]==$multi_demsional_array[$count2]['last_modified'])//condition to compare if sorted array item is equal to that of a inner loop count2
                                                                          {
                                                                               //$my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];

                                                                                     if(!empty($my_new_multi_demnsional_array[$count1]) )
                                                                                          {
                                                                                             $my_new_multi_demnsional_array[$count1+1]=$multi_demsional_array[$count2];
                                                                                                 $count1++;
                                                                                          }
                                                                                          else 
                                                                                          {
                                                                                                $my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];  
                                                                                          }

                                                                          }

                                                                }

                                        }

                        break;//end of school index case
			
                        
                case 'link'://start of school index case

                                        $my_school_sort_school_index=array();//declaration of sorting array

                                        for($count=0;$count<$size;$count++)//loop to pick index items and place them in a sorting array
                                        {
                                               $my_school_sort_school_index[$count]=$multi_demsional_array[$count]['link'];//moving through each array item and picking index item i.e. [0]
                                        }



                                       if(empty($_SESSION['my_school_sort_school_index']))//session is set but empty initally
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_DESC,SORT_NUMERIC);//sort by descending order
                                               $_SESSION['my_school_sort_school_index']=1;//then set session for next time so as to sort ascending
                                       }
                                       elseif($_SESSION['my_school_sort_school_index']==1)//now session is 1
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_ASC,SORT_NUMERIC);//sort ascending
                                               unset($_SESSION['my_school_sort_school_index']);//unset session so next time its descending
                                       }


                                        //loop to check where each item belongs and create multidimensional array

                                        for($count1=0;$count1<$size;$count1++)//outer loop to look through the sorted array items
                                        {

                                                                for($count2=0;$count2<$size;$count2++)//inner loop to look through multidemsional items to compare
                                                                {
                                                                          if($my_school_sort_school_index[$count1]==$multi_demsional_array[$count2]['link'])//condition to compare if sorted array item is equal to that of a inner loop count2
                                                                          {
                                                                               //$my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];

                                                                                     if(!empty($my_new_multi_demnsional_array[$count1]) )
                                                                                          {
                                                                                             $my_new_multi_demnsional_array[$count1+1]=$multi_demsional_array[$count2];
                                                                                                 $count1++;
                                                                                          }
                                                                                          else 
                                                                                          {
                                                                                                $my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];  
                                                                                          }

                                                                          }

                                                                }

                                        }

                        break;//end of school index case
		
                
                case 'size'://start of school index case

                                        $my_school_sort_school_index=array();//declaration of sorting array

                                        for($count=0;$count<$size;$count++)//loop to pick index items and place them in a sorting array
                                        {
                                               $my_school_sort_school_index[$count]=$multi_demsional_array[$count]['size'];//moving through each array item and picking index item i.e. [0]
                                        }



                                       if(empty($_SESSION['my_school_sort_school_index']))//session is set but empty initally
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_DESC,SORT_NUMERIC);//sort by descending order
                                               $_SESSION['my_school_sort_school_index']=1;//then set session for next time so as to sort ascending
                                       }
                                       elseif($_SESSION['my_school_sort_school_index']==1)//now session is 1
                                       {
                                               array_multisort($my_school_sort_school_index,SORT_ASC,SORT_NUMERIC);//sort ascending
                                               unset($_SESSION['my_school_sort_school_index']);//unset session so next time its descending
                                       }


                                        //loop to check where each item belongs and create multidimensional array

                                        for($count1=0;$count1<$size;$count1++)//outer loop to look through the sorted array items
                                        {

                                                                for($count2=0;$count2<$size;$count2++)//inner loop to look through multidemsional items to compare
                                                                {
                                                                          if($my_school_sort_school_index[$count1]==$multi_demsional_array[$count2]['size'])//condition to compare if sorted array item is equal to that of a inner loop count2
                                                                          {
                                                                               //$my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];

                                                                                     if(!empty($my_new_multi_demnsional_array[$count1]) )
                                                                                          {
                                                                                             $my_new_multi_demnsional_array[$count1+1]=$multi_demsional_array[$count2];
                                                                                                 $count1++;
                                                                                          }
                                                                                          else 
                                                                                          {
                                                                                                $my_new_multi_demnsional_array[$count1]=$multi_demsional_array[$count2];  
                                                                                          }

                                                                          }

                                                                }

                                        }

                        break;//end of school index case
                        
                        
                default://start of school default
                $my_new_multi_demnsional_array=$multi_demsional_array;
                break;//end of school default
				
				
	 }//end of switch case to sort school items	
	 
	 
	 
	 
	 
	/**
	 * 
	 * space between school switch above and ward switch below
	 **/	
	 
	 
	 
	 
	 
	 
	 
		
	
	
	
	
	
	
	return $my_new_multi_demnsional_array;	
		
	}//end of function my_sort_schools_array_function($multi_demsional_array)
	
	
function limit_view_tables_function($page,$array,$s,$clk,$loop_start,$sort)
{
    $s=  abs($s);//make absolute
    $loop_start=  abs($loop_start);//make absolute

    $clk=  trim(strtolower($clk));

    //if show is not set set it by default to 10
    if(isset($s) && !empty($s) && $s<=count($array))//if show is not empty and as long as its below the size of the array
    {

        //echo count($array);
         $show=$s;
    }
    else if(isset($s) && !empty($s) && $s>=count($array))//if show is not empty and as long as its above the size of the array
    {

        $show=count($array);
    }
    else if(!isset($s) && empty($s))//only set view limit to ten if array is greater than 10
    {
      $show=10;

    }
    else
    {

        $show=10; 
    }

    //if show exceeds array
    $show=$show>count($array)?count($array):$show;

    //checking page count
    if(!isset($loop_start) || empty($loop_start))
    {

      $loop_start=0;
    }

    $loop_limit;//loop limit item
    switch ($clk) 
    {//start of switch '$clk'
            case 'f'://first
                $loop_start=0;//set first item
                $loop_limit=$show;

            break;

            case 'p'://previous
                   //echo 'BEFORE start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 

                    $initial_start=$loop_start;
                    if($loop_start==0)//if the loop starts at zero
                    {
                        $loop_start=0;//set first item
                        $loop_limit=$show;
                    }
                    else if($loop_start>0 && $loop_start<count($array))
                    {
                        $loop_start-=$show;
                        $loop_limit=$loop_limit>$show ?$loop_limit-$show:$initial_start;
                    }
                    else
                    {
                        $loop_start=0;//set first item
                        $loop_limit=$show;
                    }

                  // echo 'AFTER start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 
            break;

            case 'n'://next
                //echo 'BEFORE start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 
                    if($show>count($array))//if show exceeds array size i.e cannot show what is bigger than what is there
                    {
                        $loop_start+=0;
                        $loop_limit=count($array);
                    }
                    else//else thr can be a next
                    {


                        if(count($array)-$loop_start>$show  )//if what is remaining to be shown is bigger than what is being requested to be shown
                        {
                            $loop_start+=$show;
                            $loop_limit=($loop_start+$show)>count($array)? count($array) :  $loop_start+$show;//show it

                        }
                        else 
                        {
                           //echo 'hahahh';
                            $initial_start=$loop_start;
                            $loop_limit=count($array);

                            $loop_start=($loop_start+$show)>count($array)?count($array):($loop_start+$show);
                            $loop_start=$loop_start<count($array)?$loop_start:$initial_start;//if new start is not less than count maintain initial start
                        }


                    }
                   // echo 'AFTER start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 
            break;

            case 'l'://last
               // echo 'BEFORE start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 
                        $initial_start=$loop_start;

                        $loop_limit=count($array);

                        $groups=floor(count($array)/$show);//know how many groups can be formed from selected show number
                        if( $groups*$show< count($array))//if the final show number is less than count
                        {
                            $loop_start=$groups*$show;
                        }
                        else
                        {

                            $loop_start=$initial_start==0?count($array)-$show:$initial_start;
                        }


                //echo 'AFTER start loop at: '.$loop_start.' loop limit: '.$loop_limit.' show: '.$show.' array count: '.count($array).'<br>'; 

                //echo 'start loop at: '.$loop_start.' loop limit: '.$loop_limit;
            break;


            default:
                $loop_start=0;
                $loop_limit=$show;
            break;
    }//end of switch '$clk'




   // echo $next.'--'.$last;
    $action_page=''.$page.'&sort_by='.$sort.'&s='.$show.'';


   $form='<form method="post" action="'.$action_page.'" id="search_browse_form">
           Show : <input type="number" name="s" value="'.$show.'" title="Input to specify number of items to show"/>
           <input type="submit" value="Show" title="Click to show specified items"/>
           </form>'; 

    $link_f='<a href="'.$page.'&sort_by='.$sort.'&s='.$show.'&clk=f&p='.$loop_start.'" title="Click to show first page" id="search_browse_first"> << </a> &nbsp &nbsp<a href="'.$page.'&sort_by='.$sort.'&s='.$show.'&clk=p&p='.$loop_start.'" title="Click to show previous page" id="search_browse_previous"> < </a>';


    $link_l='<a href="'.$page.'&sort_by='.$sort.'&s='.$show.'&clk=n&p='.$loop_start.'" title="Click to show next page" id="search_browse_next"> > </a> &nbsp &nbsp<a href="'.$page.'&sort_by='.$sort.'&s='.$show.'&clk=l&p='.$loop_start.'" title="Click to show last page" id="search_browse_last"> >> </a>';

    //echo $form.$link_f.'&nbsp &nbsp'.$link_l;

    $return_array=array();//return array

    $return_array[0]=$loop_start;//set start of loop
    $return_array[1]=$loop_limit;//set loop limit
    $return_array[2]=$form.$link_f.'&nbsp &nbsp'.$link_l.'<br>'.$what_page;//browse itemes
    return $return_array;
}	
	
	
//fetch assessors for drop down list
function fetch_assessors_drop_down_list($origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchAssessorLossAdjusterDetails";

     $myvars='session_key='.$_SESSION['session_key'].'&column=4&limit=999&skip=0&sort_column=full_names&sort_order=asc';

    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    $check_is=$returned_json_decoded["check"];//check
        
        
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
          
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$insurance_company=$value['insurance_company'];
                  //$employee_number=$value['employee_number'];
                  //$kra_pin=$value['kra_pin'];
                  //$rating=$value['rating'];
                  //$time_stamp=$value['time_stamp'];
                  
                  
                 $options=$options.'<option value="'.$email_address.'" >'.strtoupper($full_names).'</option>';
            }//end of foreach $message_is as $value
            
            
        }
        else//else failed
        {
            $options='<option value="" >No assessors/loss adjusters available</option>';
        }
        
        $select='<select name="assessors_loss_adjusters_list" id="assessors_loss_adjusters_list" >
                             '.$options.'
                        </select>';
        
        return $select;
}


//filter claims for assessor
function filter_claims_for_assessor_loss_adjuster($user_claims,$origin,$email_address)
{
    $return_array=array();
    
    
            //loop through the claims
            foreach ($user_claims as $value) 
            {//start of foreach $message_is as $value
                  $_id=$value['_id']['$oid'];
                  //$type_of_claim=$value['type_of_claim'];
                  //$claim_number=$value['claim_number'];
                  //$date_reported=$value['date_reported'];
                  //$company_name=$value['company_name'];
                  //$status=$value['status'];
                  //$seen_status=$value['seen_status'];
                  
                  //get that user claim assigned assessors
                  
                   //fetch assigned assesors
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchAssessorLossAdjusterAssignedClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check

                        $claim_assigned_assessors=$returned_json_decoded["message"];//message
                        
                                    
                                    if($check_is==true)//if check is true
                                    {//start of if($check_is==true)
                                                    foreach ($claim_assigned_assessors as $value_1) 
                                                    {//start of foreach $message_is as $value_1
                                                          //$claim_id=$value_1['claim_id'];
                                                          //$claim_handler_email=$value_1['claim_handler_email'];
                                                          $assessor_email=$value_1['assessor_email'];
                                                          //$time_stamp=$value_1['time_stamp'];
                                                          //$claim_handler_full_names=$value_1['claim_handler_full_names'];
                                                          //$claim_handler_phone_number=$value_1['claim_handler_phone_number'];
                                                          //$assessor_full_names=$value_1['assessor_full_names'];
                                                          //$assessor_phone_number=$value_1['assessor_phone_number'];


                                                          if($assessor_email==$email_address)//if emails match meaning it is assigned to them
                                                          {
                                                              $return_array[(count($return_array))]=$value;//assign
                                                          }
                                                          
                                                    }//end of foreach $message_is as $value_1
                                                    
                                    }//end of if if($check_is==true)
            }//end of foreach $message_is as $value
            
            return $return_array;
}


//filter clients for assessor 
function filter_clients_for_assessor_loss_adjuster($message_is,$details,$origin)
{
           $count=0;//make count skipped rows
           $message_is_return=array();
           
           
           
           
            foreach ($message_is as $value) //loop users
            {//start of foreach $message_is as $value
                  //$full_names=$value['full_names'];
                   $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                
                        //fetch user claims
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&limit=1&skip=0&email_address='.$email_address;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $message_is_is=$returned_json_decoded["message"];//message
                  
                        //fetch claims assigned to this assessor only
                        $message_is_is_is=filter_claims_for_assessor_loss_adjuster($message_is_is,$origin,$details['email_address']);
                        
                        
                        if(count($message_is_is_is)>0)
                        {
                            //assign
                            $message_is_return[$count]=$value;
                            $count++;
                        }
                        
            }//end of foreach $message_is as $value
            
        
        return $message_is_return;
}

//fetch garages for drop down list
function fetch_garages_drop_down_list($origin)
{
    //fetch
    $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchAssessorLossAdjusterDetails";

     $myvars='session_key='.$_SESSION['session_key'].'&column=5&limit=999&skip=0&sort_column=full_names&sort_order=asc';

    $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

    $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

    $returned_json_decoded= json_decode($returned_json,true);//decode
    
    $check_is=$returned_json_decoded["check"];//check
        
        
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
          
            foreach ($message_is as $value) 
            {//start of foreach $message_is as $value
                  $full_names=$value['full_names'];
                  $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$insurance_company=$value['insurance_company'];
                  //$employee_number=$value['employee_number'];
                  //$kra_pin=$value['kra_pin'];
                  //$rating=$value['rating'];
                  //$time_stamp=$value['time_stamp'];
                  
                  
                 $options=$options.'<option value="'.$email_address.'" >'.strtoupper($full_names).'</option>';
            }//end of foreach $message_is as $value
            
            
        }
        else//else failed
        {
            $options='<option value="" >No repair garages available</option>';
        }
        
        $select='<select name="repair_garages_list" id="repair_garages_list" >
                             '.$options.'
                        </select>';
        
        return $select;
}

//filter claims for garages
function filter_claims_for_repair_garage($user_claims,$origin,$email_address)
{
    $return_array=array();
    
    
            //loop through the claims
            foreach ($user_claims as $value) 
            {//start of foreach $message_is as $value
                  $_id=$value['_id']['$oid'];
                  //$type_of_claim=$value['type_of_claim'];
                  //$claim_number=$value['claim_number'];
                  //$date_reported=$value['date_reported'];
                  //$company_name=$value['company_name'];
                  //$status=$value['status'];
                  //$seen_status=$value['seen_status'];
                  
                  //get that user claim assigned assessors
                  
                   //fetch assigned assesors
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchRepairGarageAssignedClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$_id;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $check_is=$returned_json_decoded["check"];//check

                        $claim_assigned_garages=$returned_json_decoded["message"];//message
                        
                                    
                                    if($check_is==true)//if check is true
                                    {//start of if($check_is==true)
                                                    foreach ($claim_assigned_garages as $value_1) 
                                                    {//start of foreach $message_is as $value_1
                                                          //$claim_id=$value_1['claim_id'];
                                                          //$assessor_email=$value_1['assessor_email'];
                                                          $repair_garage_email=$value_1['repair_garage_email'];
                                                          //$time_stamp=$value_1['time_stamp'];
                                                          //$assessor_full_names=$value_1['assessor_full_names'];
                                                          //$assessor_phone_number=$value_1['assessor_phone_number'];
                                                          //$repair_garage_full_names=$value_1['repair_garage_full_names'];
                                                          //$repair_garage_phone_number=$value_1['repair_garage_phone_number'];


                                                          if($repair_garage_email==$email_address)//if emails match meaning it is assigned to them
                                                          {
                                                              $return_array[(count($return_array))]=$value;//assign
                                                          }
                                                          
                                                    }//end of foreach $message_is as $value_1
                                                    
                                    }//end of if if($check_is==true)
            }//end of foreach $message_is as $value
            
            return $return_array;
}

//filter clients for garage
function filter_clients_for_garage($message_is,$details,$origin)
{
           $count=0;//make count skipped rows
           $message_is_return=array();
           
           
           
           
            foreach ($message_is as $value) //loop users
            {//start of foreach $message_is as $value
                  //$full_names=$value['full_names'];
                   $email_address=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                
                        //fetch user claims
                        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchUserClaims";

                        $myvars='session_key='.$_SESSION['session_key'].'&limit=1&skip=0&email_address='.$email_address;

                        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

                        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

                        $returned_json_decoded= json_decode($returned_json,true);//decode

                        $message_is_is=$returned_json_decoded["message"];//message
                  
                        //fetch claims assigned to this assessor only
                        $message_is_is_is=filter_claims_for_repair_garage($message_is_is,$origin,$details['email_address']);
                        
                        
                        if(count($message_is_is_is)>0)
                        {
                            //assign
                            $message_is_return[$count]=$value;
                            $count++;
                        }
                        
            }//end of foreach $message_is as $value
            
        
        return $message_is_return;
}


//function to check if a claim has a garage report
function check_if_claim_has_garage_report($claim_id,$origin)
{
        //fetch reports
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.FetchClaimCostSheets";

        $myvars='session_key='.$_SESSION['session_key'].'&claim_id='.$claim_id;
        
        $header_array= array('Cookie:'.$_SESSION['cookie'],'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        
         
        $returned_json_decoded= json_decode($returned_json,true);//decode

        $check_is=$returned_json_decoded["check"];//check

        $message_is=$returned_json_decoded["message"];//message

        if($check_is==true)//if check is true
        {
                $return =false;
                
                
                foreach ($message_is as $value) 
                {//start of foreach $message_is as $value
                      //$_id_is=$value['_id']['$oid'];
                      $claim_id_is=$value['claim_id'];      
                      //$repair_garage_email_is=$value['repair_garage_email'];
                      //$repair_garage_full_names_is=$value['repair_garage_full_names'];
                      //$repair_garage_phone_number_is=$value['repair_garage_phone_number'];
                      //$time_stamp_is=$value['time_stamp'];


                     if($claim_id==$claim_id_is)
                     {
                         $return =true;
                     }

                }//end of foreach $message_is as $value

                return $return;
        }
        else//else failed
        {

            return false;
        } 
}

//function login on behalf of client
function login_behalf_of_client($username,$origin)
{
            $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.ClientLoginLink";

            $myvars='username='.$username;

            $header_array= array('Authorization:'.api_key_is(),'Origin:'.$origin);

            $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output

            //echo $returned_json;
            $returned_json_decoded= json_decode($returned_json,true);//decode

            $check_is=$returned_json_decoded["check"];//check

            $message_is=$returned_json_decoded["message"];//message
            
            if($check_is==true)//if check is true
            {

                 return $message_is;
            }
            else//else failed
            {

                return false;
            } 
}

//function send message
function send_sms_message($session_key,$cookie,$message,$phone_number,$origin)
{
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.SendSMS";

        $myvars='session_key='.$session_key.'&message='.$message.'&phone_number='.$phone_number;

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         //$message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        { 
                 return true; 
        }
        else//else failed
        {
           return false;
        }
}

//function email message
function send_email_message($session_key,$cookie,$email_address,$header,$message,$origin)
{
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.SendEmail";

        $myvars='session_key='.$session_key.'&message='.$message.'&header='.$header.'&email_address='.$email_address;

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         //$message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        { 
                 return true; 
        }
        else//else failed
        {
           return false;
        }
}

function send_email_free_message($email_address,$header,$message,$origin)
{
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.SendEmailFree";

        $myvars='message='.$message.'&header='.$header.'&email_address='.$email_address;

        $header_array= array('Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
         //$message_is=$returned_json_decoded["message"];//message
        //draw
        if($check_is==true)//if check is true
        { 
                 return true; 
        }
        else//else failed
        {
           return false;
        }
}


function get_specific_client_details($session_key,$cookie,$email_address,$origin)
{
        //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchClientDetails";

        $myvars='session_key='.$session_key.'&limit=99999&skip=0&sort_column=time_stamp&sort_order=asc';

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        $array=array();
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
            for ($index = 0; $index < count($message_is); $index++) 
            {
                $value=$message_is[$index];
                
                 //$full_names=$value['full_names'];
                  $email_address_is=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                  
                        if ($email_address_is==$email_address) 
                        {
                            $array=$value;
                            //exit loop
                            $index=count($message_is);
                        }
            }
            
            
            
        }
        
        return $array;
}


function get_specific_assessor_details($session_key,$cookie,$email_address,$origin)
{
        //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchAssessorLossAdjusterDetails";

        $myvars='session_key='.$session_key.'&column=4&limit=99999&skip=0&sort_column=time_stamp&sort_order=asc';

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        $array=array();
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
            for ($index = 0; $index < count($message_is); $index++) 
            {
                $value=$message_is[$index];
                
                 //$full_names=$value['full_names'];
                  $email_address_is=$value['email_address'];
                  //$phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                  
                        if ($email_address_is==$email_address) 
                        {
                            $array=$value;
                            //exit loop
                            $index=count($message_is);
                        }
            }
            
            
            
        }
        
        return $array;
}

function get_specific_repair_garage_details($session_key,$cookie,$email_address,$origin)
{
        //fetch
        $url_is=the_api_authentication_api_url_is()."denkimAPILogic/MainPackages.AdministratorFetchRepairGarageDetails";

        $myvars='session_key='.$session_key.'&column=5&limit=99999&skip=0&sort_column=time_stamp&sort_order=asc';

        $header_array= array('Cookie:'.$cookie,'Authorization:'.api_key_is(),'Origin:'.$origin);

        $returned_json=send_curl_post($url_is,$myvars,$header_array);//cap output
        
        $returned_json_decoded= json_decode($returned_json,true);//decode
        
        $check_is=$returned_json_decoded["check"];//check
        
        $array=array();
        //draw
        if($check_is==true)//if check is true
        {
            
            $message_is=$returned_json_decoded["message"];//message
            
            for ($index = 0; $index < count($message_is); $index++) 
            {
                $value=$message_is[$index];
                
                 //$full_names=$value['full_names'];
                  $email_address_is=$value['email_address'];
                 // $phone_number=$value['phone_number'];
                  //$national_id=$value['national_id'];
                  //$time_stamp=$value['time_stamp'];
                  
                  
                        if ($email_address_is==$email_address) 
                        {
                            $array=$value;
                            //exit loop
                            $index=count($message_is);
                        }
            }
            
            
            
        }
        
        return $array;
}

//function cart counter
function count_the_cart($shoping_cart)
{
    $count_is=0;
    foreach ($shoping_cart as $key => $value) 
    {
        $count_is+= count($value);
    }
    
    $statement_is=$count_is==1? $count_is.' item in cart': $count_is.' items in cart';
    return $statement_is;
}


//function to get aggregate totals of payments
function get_aggregate_totals_payments_client($session_key,$cookie)
{
    
}