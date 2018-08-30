// JavaScript Document\
//hover link
var father_max_min_fields_count=0;
var mother_max_min_fields_count=0;
var children_max_min_fields_count=0;

function father_max_min_fields()
{
	
	var field_min='<input type="number" name="father_max_min_fields_min[]"  placeholder="Minimum"/>';
        var field_max='<input type="number" name="father_max_min_fields_max[]"  placeholder="Minimum"/>';
        var ol_id="father_options_sub_"+father_max_min_fields_count;
        var add_sub_items_link='<custom_clicky_thingy onclick="father_max_min_fields_sub(\''+ol_id+'\',\''+father_max_min_fields_count+'\');">Add father limit and primium fields</custom_clicky_thingy>';
        var ol_item=add_sub_items_link+'<ol id="'+ol_id+'" class="father_options_sub" ></ol>';
        $('#father_options').append('<li><table><tr><td>'+field_min+'</td><td>'+field_max+'</td></tr></table></li>'+ol_item);
	father_max_min_fields_count++;
	
}

function father_max_min_fields_sub(ol_id,count)
{
	
	
	//alert(ol_id+'--'+count);
        var limit='<input type="number" name="father_max_min_fields_sub_limit_'+count+'[]"  placeholder="Limit"/>';
        var premium='<input type="number" name="father_max_min_fields_sub_premium_'+count+'[]"  placeholder="Premium"/>';
        
         $('#'+ol_id).append('<table><li><tr><td>'+limit+'</td><td>'+premium+'</td></tr></li></table>');
	
}



function mother_max_min_fields()
{
	
	var field_min='<input type="number" name="mother_max_min_fields_min[]"  placeholder="Minimum"/>';
        var field_max='<input type="number" name="mother_max_min_fields_max[]"  placeholder="Minimum"/>';
        var ol_id="mother_options_sub_"+mother_max_min_fields_count;
        var add_sub_items_link='<custom_clicky_thingy onclick="mother_max_min_fields_sub(\''+ol_id+'\',\''+mother_max_min_fields_count+'\');">Add mother limit and primium fields</custom_clicky_thingy>';
        var ol_item=add_sub_items_link+'<ol id="'+ol_id+'" class="mother_options_sub" ></ol>';
        $('#mother_options').append('<li><table><tr><td>'+field_min+'</td><td>'+field_max+'</td></tr></table></li>'+ol_item);
	mother_max_min_fields_count++;
	
}

function mother_max_min_fields_sub(ol_id,count)
{
	
	
	//alert(ol_id+'--'+count);
        var limit='<input type="number" name="mother_max_min_fields_sub_limit_'+count+'[]"  placeholder="Limit"/>';
        var premium='<input type="number" name="mother_max_min_fields_sub_premium_'+count+'[]"  placeholder="Premium"/>';
        
         $('#'+ol_id).append('<table><li><tr><td>'+limit+'</td><td>'+premium+'</td></tr></li></table>');
	
}


function children_max_min_fields()
{
	
	var field_min='<input type="number" name="children_max_min_fields_min[]"  placeholder="Minimum"/>';
        var field_max='<input type="number" name="children_max_min_fields_max[]"  placeholder="Minimum"/>';
        var ol_id="children_options_sub_"+children_max_min_fields_count;
        var add_sub_items_link='<custom_clicky_thingy onclick="children_max_min_fields_sub(\''+ol_id+'\',\''+children_max_min_fields_count+'\');">Add children limit and primium fields</custom_clicky_thingy>';
        var ol_item=add_sub_items_link+'<ol id="'+ol_id+'" class="children_options_sub" ></ol>';
        $('#children_options').append('<li><table><tr><td>'+field_min+'</td><td>'+field_max+'</td></tr></table></li>'+ol_item);
	children_max_min_fields_count++;
	
}

function children_max_min_fields_sub(ol_id,count)
{
	
	
	//alert(ol_id+'--'+count);
        var limit='<input type="number" name="children_max_min_fields_sub_limit_'+count+'[]"  placeholder="Limit"/>';
        var premium='<input type="number" name="children_max_min_fields_sub_premium_'+count+'[]"  placeholder="Premium"/>';
        
         $('#'+ol_id).append('<table><li><tr><td>'+limit+'</td><td>'+premium+'</td></tr></li></table>');
	
}
