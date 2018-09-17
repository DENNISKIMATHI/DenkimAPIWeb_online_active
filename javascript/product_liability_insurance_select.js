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
                
		$('#limit').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
				var minimum_is=parseInt($(this).attr('min'));
                                var premium_percentage_is=$(this).attr('premium_percentage_is');
                                var limit=$(this).val();
                                
                                
                                
				if(limit<=minimum_is)
                                {
                                    
                                    limit=minimum_is;
                                }
				
					//if empty clear div
					if(limit==='')
					{
						$('#premium_span').html('');//clear
					}
                                        else
                                        {
                                            var x_is=limit*premium_percentage_is;
                                             var y_is=x_is *(0.45/100);
                                             
                                                var premium=x_is+y_is+40;
                                                
                                                $('#premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
                
                
                 
                 
             
	}//end of document ready
                
              
);

                
               