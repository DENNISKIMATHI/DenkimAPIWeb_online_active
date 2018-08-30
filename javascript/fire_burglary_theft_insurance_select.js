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
                
		$('#fire_price').keyup(function()//mobile
			{
                           
                                $('#fire_premium_span').html('');//clear
				var fire_multiplier_is=$(this).attr('fire_multiplier_is');
                                var fire_price=$(this).val();
                                
				
				
					//if empty clear div
					if(fire_price==='')
					{
						$('#fire_premium_span').html('');//clear
					}
                                        else
                                        {
                                                var w_is=fire_multiplier_is*fire_price;
                                                var x_is=w_is *0.0045;
                                                var premium=x_is+w_is+40;
                                                $('#fire_premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
                $('#burglary_price').keyup(function()//mobile
			{
                           
                                $('#burglary_premium_span').html('');//clear
				var burglary_multiplier_is=$(this).attr('burglary_multiplier_is');
                                var burglary_price=$(this).val();
                                
				
				
					//if empty clear div
					if(burglary_price==='')
					{
						$('#burglary_premium_span').html('');//clear
					}
                                        else
                                        {
                                                var w_is=burglary_multiplier_is*burglary_price;
                                                var x_is=w_is *0.0045;
                                                var premium=x_is+w_is+40;
                                                $('#burglary_premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
                
                $('#all_risk_price').keyup(function()//mobile
			{
                           
                                $('#all_risk_premium_span').html('');//clear
				var all_risk_multiplier_is=$(this).attr('all_risk_multiplier_is');
                                var all_risk_price=$(this).val();
                                
				
				
					//if empty clear div
					if(all_risk_price==='')
					{
						$('#all_risk_premium_span').html('');//clear
					}
                                        else
                                        {
                                                var w_is=all_risk_multiplier_is*all_risk_price;
                                                var x_is=w_is *0.0045;
                                                var premium=x_is+w_is+40;
                                                $('#all_risk_premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
               
                 
             
	}//end of document ready
                
              
);

                
               