var current_max_categories=0;

function add_category(how_many_categories)
{
	
        
	current_max_categories=current_max_categories===0?( parseInt(how_many_categories)+1):( parseInt(current_max_categories)+1);
        var span_id_is="wiba_categories_span_"+( current_max_categories);
        
        
        
       
         var category_name="<input type=\"text\"  name=\"wiba_categories["+current_max_categories+"][category_name]\"  required  placeholder=\"Category name\" />";
          var category_multiplier="<input type=\"number\"  name=\"wiba_categories["+current_max_categories+"][category_multiplier]\" step=any required  placeholder=\"Category multiplier\" />";
                  
     
        var button_name_is="button_name_categories_is_"+( current_max_categories);
         $('#wiba_categories_are').append("<span id=\""+span_id_is+"\">"+category_name+category_multiplier+"</span> <input type=\"button\" id=\""+button_name_is+"\" value=\"Remove\" onclick=\"remove_category('"+span_id_is+"','"+button_name_is+"')\"/><br>")
  
	
}


function remove_category(span_id_is,button_name_is)
{
	
	//alert(phone_name_is);
	$('#'+span_id_is).remove();
        $('#'+button_name_is).remove();
        current_max_benfites=(parseInt(current_max_benfites))-1;
}



var current_max_liabilities=0;

function add_employee_liabilities(how_many_categories)
{
	
        
	current_max_liabilities=current_max_liabilities===0?( parseInt(how_many_categories)+1):( parseInt(current_max_liabilities)+1);
        var span_id_is="employee_liabilities_span_"+( current_max_liabilities);
        
        
        
       
         var option_name="<input type=\"text\"  name=\"employee_liability_options["+current_max_liabilities+"][option_name]\"  required  placeholder=\"Option name\" />";
         // var limit="<input type=\"number\"  name=\"employee_liability_options["+current_max_liabilities+"][limit]\" required  placeholder=\"Limit\" />";
          var multiplier="<input type=\"number\"  name=\"employee_liability_options["+current_max_liabilities+"][multiplier]\" required step=any placeholder=\"Multiplier\" />";
          var html="<input type=\"text\"  name=\"employee_liability_options["+current_max_liabilities+"][html]\"  required  placeholder=\"HTML\" />";
                 
     
        var button_name_is="button_name_liabilities_is_"+( current_max_liabilities);
         $('#employee_liabilities_are').append("<span id=\""+span_id_is+"\">"+option_name+multiplier+html+"</span> <input type=\"button\" id=\""+button_name_is+"\" value=\"Remove\" onclick=\"remove_employee_liabilities('"+span_id_is+"','"+button_name_is+"')\"/><br>")
  
	
}


function remove_employee_liabilities(span_id_is,button_name_is)
{
	
	//alert(phone_name_is);
	$('#'+span_id_is).remove();
        $('#'+button_name_is).remove();
        current_max_liabilities=(parseInt(current_max_liabilities))-1;
}