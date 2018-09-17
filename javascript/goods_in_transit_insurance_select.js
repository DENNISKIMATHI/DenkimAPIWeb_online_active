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
                
		$('#estimated_carry_on_any_trip').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
				var a_percentage_is=$(this).attr('a_percentage_is');
                                var b_percentage_is=$(this).attr('b_percentage_is');
                                
                                var estimated_carry_on_any_trip_value=$('#estimated_carry_on_any_trip').val();
                                var total_annual_estimated_carry_all_trips_value=$('#total_annual_estimated_carry_all_trips').val();
				
				if(estimated_carry_on_any_trip_value!=='' && total_annual_estimated_carry_all_trips_value!=='')
                                {
                                    
                                            var v_is=a_percentage_is*estimated_carry_on_any_trip_value;
                                            var w_is=b_percentage_is*total_annual_estimated_carry_all_trips_value;
                                            
                                            var x_is=v_is+w_is;
                                            
                                            var y_is=x_is*(0.45/100);
                                            
                                                    
                                                
                                                var premium=x_is+y_is+40;
                                                $('#premium_span').html(format_kes(premium));//clear
                                      
                                }
					
				
			}
		);
        
               $('#total_annual_estimated_carry_all_trips').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
				var a_percentage_is=$(this).attr('a_percentage_is');
                                var b_percentage_is=$(this).attr('b_percentage_is');
                                
                                var estimated_carry_on_any_trip_value=$('#estimated_carry_on_any_trip').val();
                                var total_annual_estimated_carry_all_trips_value=$('#total_annual_estimated_carry_all_trips').val();
				
				if(estimated_carry_on_any_trip_value!=='' && total_annual_estimated_carry_all_trips_value!=='')
                                {
                                    
                                            var v_is=a_percentage_is*estimated_carry_on_any_trip_value;
                                            var w_is=b_percentage_is*total_annual_estimated_carry_all_trips_value;
                                            
                                            var x_is=v_is+w_is;
                                            
                                            var y_is=x_is*(0.45/100);
                                            
                                                    
                                                
                                                var premium=x_is+y_is+40;
                                                $('#premium_span').html(format_kes(premium));//clear
                                      
                                }
					
				
			}
		);
                 
                 
             
	}//end of document ready
                
              
);

                
               