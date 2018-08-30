// JavaScript Document
$(document).ready(function() 
	{
		
		
		function format_kes(num) 
                {
                    var p = num.toFixed(2).split(".");
                    return "KES. " + p[0].split("").reverse().reduce(function(acc, num, i, orig) 
                    {
                        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
                    }, "") + "." + p[1];
                }
                
		$('#building_value').keyup(function()//mobile
			{
                           
                                $('#building_value_span').html('');//clear
				var building_value_multiplier_is=$(this).attr('building_value_multiplier_is');
                                var building_value=$(this).val();
                                
				
				
					//if empty clear div
					if(building_value==='')
					{
						$('#building_value_span').html('');//clear
					}
                                        else
                                        {
                                                var a_is=building_value_multiplier_is*building_value;
                                                var b_is=a_is *0.0045;
                                                var premium=a_is+b_is+40;
                                                $('#building_value_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
               
               $('#content_value').keyup(function()//mobile
			{
                           
                                $('#content_value_span').html('');//clear
				var content_value_multiplier_is=$(this).attr('content_value_multiplier_is');
                                var content_value=$(this).val();
                                
				
				
					//if empty clear div
					if(content_value==='')
					{
						$('#content_value_span').html('');//clear
					}
                                        else
                                        {
                                                var a_is=content_value_multiplier_is*content_value;
                                                var b_is=a_is *0.0045;
                                                var premium=a_is+b_is+40;
                                                $('#content_value_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
                $('#electronics_value').keyup(function()//mobile
			{
                           
                                $('#electronics_value_span').html('');//clear
				var electronics_value_multiplier_is=$(this).attr('electronics_value_multiplier_is');
                                var electronics_value=$(this).val();
                                
				
				
					//if empty clear div
					if(electronics_value==='')
					{
						$('#electronics_value_span').html('');//clear
					}
                                        else
                                        {
                                                var a_is=electronics_value_multiplier_is*electronics_value;
                                                var b_is=a_is *0.0045;
                                                var premium=a_is+b_is+40;
                                                $('#electronics_value_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
                 
             
	}//end of document ready
                
              
);

                
               