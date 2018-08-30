// JavaScript Document\
//hover link
var header_fields_count=0;
var reference_fields_count=0;
var body_fields_count=0;
var damage_fields_count=0;
var estimated_cost_fields_count=0;


var totals_array=[];

function header_fields()
{
	
	var field='<input type="text" name="header_fields[]"  placeholder="Header"/>';
        $('#header_data').append('<li><table><tr><td>'+field+'</td></tr></table></li>');
	header_fields_count++;
	
}

function reference_fields()
{
	
	var field='<input type="text" name="reference_fields[]"  placeholder="Reference"/>';
        $('#reference_data').append('<li><table><tr><td>'+field+'</td></tr></table></li>');
	reference_fields_count++;
	
}



function body_fields()
{
	
	var body_fields_1='<input type="text" name="body_fields_1[]"  placeholder="Body title"/>';
        var body_fields_2='<input type="text" name="body_fields_2[]"  placeholder="Body information"/>';
        
        
        $('#body_data').append('<li><table><tr><td>'+body_fields_1+'</td><td>'+body_fields_2+'</td></tr></table></li>');
	body_fields_count++;
	
}

function damage_fields()
{
	
	var field='<input type="text" name="damage_fields[]"  placeholder="Damage"/>';
        $('#damage_data').append('<li><table><tr><td>'+field+'</td></tr></table></li>');
	damage_fields_count++;
	
}


function estimated_cost_fields()
{
	
        var unique_id="unique_cost_field_name_"+estimated_cost_fields_count;
        var on_estimated_total_function='onkeyup="do_estimated_total_math(\''+unique_id+'\');"';
       
       
	var estimated_cost_fields_1='<input type="text" name="estimated_cost_fields_1[]"  placeholder="Cost title"/>';
        var estimated_cost_fields_2='<input type="number" name="estimated_cost_fields_2[]"  id="'+unique_id+'" placeholder="Actual cost" '+on_estimated_total_function+'/>';
        
        
         
        $('#estimated_cost_data').append('<li><table><tr><td>'+estimated_cost_fields_1+'</td><td>'+estimated_cost_fields_2+'</td></tr></table></li>');
	estimated_cost_fields_count++;
	
}


// Create our number formatter.
var formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'KES',
  minimumFractionDigits: 2,
  // the default value for minimumFractionDigits depends on the currency
  // and is usually already 2
});

function do_estimated_total_math(field_name)
{
    
    var number_is=parseInt($('#'+field_name).val());
    
    totals_array[field_name]=number_is;
    
        
    var estimated_total=0;
    
        for (var key in totals_array) 
        {
            var value = totals_array[key];
            //alert(value);
            estimated_total=estimated_total+value;
            //console.log(key, value);
        }
   
    $("#estimated_total").val(formatter.format(estimated_total));
    
}