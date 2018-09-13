// JavaScript Document

var counter=0;
var multiplier=0;
var items = {}; 
var how_many_spans=0;

                function format_kes(num) 
                {
                    var p = num.toFixed(2).split(".");
                    return "KES. " + p[0].split("").reverse().reduce(function(acc, num, i, orig) 
                    {
                        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
                    }, "") + "." + p[1];
                }
                
                
		function add_employees_for_wiba(id_for_use,div_id,category_multiplier,category_name)
                {
                        
                        counter++;
                       
                        
                         var input_box_is="input_box_is_"+counter+"_"+(id_for_use);
                         var span_id_is="wiba_categories_span_"+counter+"_"+(id_for_use);
                         var button_name_is="button_name_categories_is_"+counter+"_"+(id_for_use);
                         
                         var employee_id="<input type=\"text\"  name=\"selected_employee_categories["+category_name+"]["+span_id_is+"][employee_id]\"  required  placeholder=\"Employee id\" /><br>";
                         var monthly_salary="<input type=\"number\" min=1 id=\""+input_box_is+"\" name=\"selected_employee_categories["+category_name+"]["+span_id_is+"][monthly_salary]\" step=any required  placeholder=\"Monthly salary\" onchange=\"add_record_category_premium('"+input_box_is+"','"+span_id_is+"','"+category_multiplier+"')\"/><br>";

                        
                         $('#'+div_id).append("<span id=\""+span_id_is+"\">"+employee_id+monthly_salary+"</span><br> <input type=\"button\" id=\""+button_name_is+"\" value=\"Remove\" onclick=\"remove_category('"+span_id_is+"','"+button_name_is+"')\"/><br><br>");
                         
                }
                
                function remove_category(span_id_is,button_name_is)
                {

                        how_many_spans=how_many_spans-1;
                        
                        //alert(phone_name_is);
                        $('#'+span_id_is).remove();
                        $('#'+button_name_is).remove();
                        delete items[span_id_is];
                        var premium_is=get_premium_total();
                        var option_is=get_option_total();
                         $('#premium_span').html(format_kes( (premium_is+option_is) ) );
                }
                
                
                function add_record_category_premium(input_box_is,span_id_is,category_multiplier)
                {
                    var item_is = {};
                    item_is['input_box_is']=input_box_is;
                    item_is['category_multiplier']=category_multiplier;
                    
                    
                     
                    items[span_id_is]=item_is;
                    
                   var premium_is=get_premium_total();
                   var option_is=get_option_total();
                  
                    $('#premium_span').html(format_kes( (premium_is+option_is) ) );
                   
                }
                
                function record_option(multiplier_is,radio_id)
                {
                    
                        if($('#'+radio_id).is(':checked') )
                        { 
                           
                              multiplier=multiplier_is;
                              
                                var premium_is=get_premium_total();
                                var option_is=get_option_total();
                                 $('#premium_span').html(format_kes( (premium_is+option_is) ) );
                              
                        }
                }
                
                function get_option_total()
                {
                    if (multiplier>0)
                    {
                            var premium_is=get_premium_total();
                            var u_is=premium_is*multiplier;
                            var t_is=u_is*0.45;

                            return (u_is+t_is+40);
                    }
                    else
                    {
                      return 0;  
                    }
                   
                    
                    
                   
                    
                }
                
                function get_premium_total()
                {
                    var total=0;
                    
                    
                    
                        $.each( items, function( key, val ) 
                        {
                            var  value_is=parseInt($('#'+val['input_box_is']).val());
                            
                            var a_is=value_is*val['category_multiplier']*12;
                            total=total+a_is;
                            
                            
                        });
                        
                        var v_is=total*0.45;
                        var premium=v_is+total+40;
                        
                        
                        return premium;
                   
                    
                }