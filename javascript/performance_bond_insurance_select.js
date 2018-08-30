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
                
		$('#contract_price').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
				var contract_price_multiplier_is=$(this).attr('contract_price_multiplier_is');
                                var w_multiplier_is=$(this).attr('w_multiplier_is');
                                var contract_price=$(this).val();
                                
				
				
					//if empty clear div
					if(contract_price==='')
					{
						$('#premium_span').html('');//clear
					}
                                        else
                                        {
                                                var w_is=contract_price_multiplier_is*contract_price;
                                                var x_is=w_is *w_multiplier_is;
                                                var y_is=x_is *0.0045;
                                                var premium=x_is+w_is+y_is+40;
                                                $('#premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
               
                 
             
	}//end of document ready
                
              
);

                
               