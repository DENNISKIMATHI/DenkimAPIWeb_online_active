// JavaScript Document\
//hover link


function options_fields()
{
	
	var field_options='<input type="text" name="options[]"  placeholder="Option name"/>';
        var field_premiums='<input type="number" name="premiums[]"  placeholder="Premium"/>';
        var field_html='<input type="text" name="htmls[]"  placeholder="HTML link"/>';
        
        $('#options_list').append('<li><table><tr><td>'+field_options+'</td><td>'+field_premiums+'</td><td>'+field_html+'</td></tr></table></li>');
	
	
}


