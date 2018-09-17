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
                
                function add_employees_for_wiba_plan_b()
                {
                     
                         var category_name_is= $('#list_of_categories').val(); 
                         if(category_name_is!=="")
                         {
                               var id_for_use = $('select#list_of_categories option:selected').attr('id_for_use')
                               var category_multiplier = $('select#list_of_categories option:selected').attr('category_multiplier')
                               var category_name = $('select#list_of_categories option:selected').attr('category_name')
                                
                              $('#ädd_employee_btn').attr('onclick','add_employees_for_wiba(\''+id_for_use+'\',\'some_div_name_never_do_this\',\''+category_multiplier+'\',\''+category_name+'\')');
                        
                            
                         }
                }
                
		function add_employees_for_wiba(id_for_use,div_id,category_multiplier,category_name)
                {
                        
                        counter++;
                       
                        var employee_id_is=$('#employee_id_for_use_is').val();
                        var employee_salary_is=$('#employee_salary_for_use_is').val();
                        
                         var input_box_is="input_box_is_"+counter+"_"+(id_for_use);
                         var span_id_is="wiba_categories_span_"+counter+"_"+(id_for_use);
                         var button_name_is="button_name_categories_is_"+counter+"_"+(id_for_use);
                         
                         var employee_id="<input type=\"text\"  name=\"selected_employee_categories["+category_name+"]["+span_id_is+"][employee_id]\"  required  placeholder=\"Employee id\" value=\""+employee_id_is+"\" /><br>";
                         var monthly_salary="<input type=\"number\" min=1 id=\""+input_box_is+"\" name=\"selected_employee_categories["+category_name+"]["+span_id_is+"][monthly_salary]\" step=any required  placeholder=\"Monthly salary\" value=\""+employee_salary_is+"\" onkeyup=\"add_record_category_premium('"+input_box_is+"','"+span_id_is+"','"+category_multiplier+"')\"/><br>";

                         $('#employee_id_for_use_is').val("");
                         $('#employee_salary_for_use_is').val("");
                         
                         $('#'+div_id).append("<span id=\""+span_id_is+"\"><h4>"+category_name+"</h4>"+employee_id+monthly_salary+"</span><br> <input type=\"button\" id=\""+button_name_is+"\" value=\"Remove\" onclick=\"remove_category('"+span_id_is+"','"+button_name_is+"')\"/><br><br>");
                         add_record_category_premium(input_box_is,span_id_is,category_multiplier);
                         $('#list_of_categories option:first').prop('selected',true);
                         
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
                            var t_is=u_is*(0.45/100);

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
                        
                        var v_is=total*(0.45/100);
                        var premium=v_is+total+40;
                        
                        
                        return premium;
                   
                    
                }