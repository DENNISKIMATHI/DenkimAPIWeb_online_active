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
                
		$('#insured_item_value').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
				var premium_percentage_is=$(this).attr('premium_percentage_is');
                                var insured_item_value=$(this).val();
                                
				
				
					//if empty clear div
					if(insured_item_value=='')
					{
						$('#premium_span').html('');//clear
					}
                                        else
                                        {
                                                var x_is=(premium_percentage_is/100)*insured_item_value;
                                                var y_is=x_is *(0.45/100);
                                                var premium=x_is+y_is+40;
                                                $('#premium_span').html(format_kes(premium));//clear
                                        }
				
				
			}
		);
        
                $('#excess_protector_percentage_is').click(function()//mobile
                {

                        $('#excess_protector_percentage_is_span').html('');//clear
                        var excess_protector_percentage_is=$(this).attr('excess_protector_percentage_is');
                        var checked_status=$(this).attr('status_is');
                        var insured_item_value=$('#insured_item_value').val();



                                //if empty clear div
                                if(checked_status=='checked')//if checked clear
                                {
                                         $('#excess_protector_percentage_is_span').html('');//clear
                                         $(this).attr('status_is','unchecked');//make unchecked
                                }
                                else
                                {
                                            var ans=(excess_protector_percentage_is/100)*insured_item_value;
                                            
                                            $('#excess_protector_percentage_is_span').html( format_kes (ans) );//clear
                                            $(this).attr('status_is','checked');//make checked
                                }


                }
		);
        
                $('#political_risk_terrorism_percentage_is').click(function()//mobile
                {

                        $('#political_risk_terrorism_percentage_is_span').html('');//clear
                        var political_risk_terrorism_percentage_is=$(this).attr('political_risk_terrorism_percentage_is');
                        var checked_status=$(this).attr('status_is');
                        var insured_item_value=$('#insured_item_value').val();



                                //if empty clear div
                                if(checked_status=='checked')//if checked clear
                                {
                                         $('#political_risk_terrorism_percentage_is_span').html('');//clear
                                         $(this).attr('status_is','unchecked');//make unchecked
                                }
                                else
                                {
                                            var ans=(political_risk_terrorism_percentage_is/100)*insured_item_value;
                                            
                                            $('#political_risk_terrorism_percentage_is_span').html( format_kes (ans) );//clear
                                            $(this).attr('status_is','checked');//make checked
                                }


                }
		);
                
                 
                 
             
	}//end of document ready
                
              
);

                
               