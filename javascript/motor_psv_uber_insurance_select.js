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
                
		$('#vehicle_value').keyup(function()//mobile
			{
                           
                                $('#premium_span').html('');//clear
                                $('#premium_span').html(format_kes(return_the_premium_motor_psv()));//clear
			}
		);
        
                $('#number_of_passengers').keyup(function()//mobile
                {
                    
                        $('#premium_span').html('');//clear
                        $('#excess_protector_percentage_is_span').html('');//clear
                        $('#political_risk_terrorism_percentage_is_span').html('');//clear
                       
                        var total_premium=return_the_premium_motor_psv()+return_the_excess_protector_motor_psv()+ return_the_political_violence_motor_psv()+return_the_aa_membership_motor_psv();
                        $('#premium_span').html(format_kes(total_premium));//clear
                        
                         $('#excess_protector_percentage_is_span').html(format_kes(return_the_excess_protector_motor_psv()));//clear
                        $('#political_risk_terrorism_percentage_is_span').html(format_kes(return_the_political_violence_motor_psv()));//clear
                }
		);
        
                $('#excess_protector_percentage').click(function()//mobile
                {

                        $('#premium_span').html('');//clear
                        $('#excess_protector_percentage_is_span').html('');//clear
                        $('#political_risk_terrorism_percentage_is_span').html('');//clear
                        
                        var total_premium=return_the_premium_motor_psv()+return_the_excess_protector_motor_psv()+ return_the_political_violence_motor_psv()+return_the_aa_membership_motor_psv();
                        $('#premium_span').html(format_kes(total_premium));//clear
                        
                         $('#excess_protector_percentage_is_span').html(format_kes(return_the_excess_protector_motor_psv()));//clear
                        $('#political_risk_terrorism_percentage_is_span').html(format_kes(return_the_political_violence_motor_psv()));//clear

                }
		);
        
                $('#political_risk_terrorism_percentage').click(function()//mobile
                {

                        $('#premium_span').html('');//clear
                        $('#excess_protector_percentage_is_span').html('');//clear
                        $('#political_risk_terrorism_percentage_is_span').html('');//clear
                        
                        var total_premium=return_the_premium_motor_psv()+return_the_excess_protector_motor_psv()+ return_the_political_violence_motor_psv()+return_the_aa_membership_motor_psv();
                        $('#premium_span').html(format_kes(total_premium));//clear
                        
                         $('#excess_protector_percentage_is_span').html(format_kes(return_the_excess_protector_motor_psv()));//clear
                        $('#political_risk_terrorism_percentage_is_span').html(format_kes(return_the_political_violence_motor_psv()));//clear


                }
		);
                
                $('#aa_membership').click(function()//mobile
                {

                        $('#premium_span').html('');//clear
                        $('#excess_protector_percentage_is_span').html('');//clear
                        $('#political_risk_terrorism_percentage_is_span').html('');//clear
                        
                        var total_premium=return_the_premium_motor_psv()+return_the_excess_protector_motor_psv()+ return_the_political_violence_motor_psv()+return_the_aa_membership_motor_psv();
                        $('#premium_span').html(format_kes(total_premium));//clear
                        
                         $('#excess_protector_percentage_is_span').html(format_kes(return_the_excess_protector_motor_psv()));//clear
                        $('#political_risk_terrorism_percentage_is_span').html(format_kes(return_the_political_violence_motor_psv()));//clear


                }
		);
        
                 function return_the_premium_motor_psv()
                 {
                     var premium;
                                var v_percentage=$('#vehicle_value').attr('v_percentage');
                                var n_percentage=$('#vehicle_value').attr('n_percentage');
                                
                                var vehicle_value=$('#vehicle_value').val();
                                var number_of_passengers=$('#number_of_passengers').val();
				
				
					//if empty clear div
					if(vehicle_value!=='' && number_of_passengers!=='')
					{
                                              var  w_is=v_percentage* vehicle_value;
                                              var  x_is=n_percentage* number_of_passengers;
                                               
						 var y_is=x_is +w_is;
                                                 var z_is=y_is*(0.45/100);
                                                 
                                                  premium=z_is+y_is+40;
					}
                                        
                                        return premium;
                 }
                 
                 
                 function return_the_excess_protector_motor_psv()
                 {
                     
                     var ans=0;
                                var minimum_excess_protector=parseInt($('#excess_protector_percentage').attr('minimum_excess_protector'));
                                var excess_protector_multiplier=$('#excess_protector_percentage').attr('excess_protector_multiplier');
                                
                                var vehicle_value=$('#vehicle_value').val();
                                 var number_of_passengers=$('#number_of_passengers').val();
                                 
				if($('#excess_protector_percentage').is(':checked') && vehicle_value!=='' && number_of_passengers!=='')
                                {
                                     var  k_is=excess_protector_multiplier* vehicle_value;
                                     var  l_is=(0.45/100)* k_is;
                                     var total=l_is+k_is;
                                     
                                     ans=total<=minimum_excess_protector?minimum_excess_protector:total;
                                              
                                }
				
			return ans;		
                                     
                 }
                 
                 function return_the_political_violence_motor_psv()
                 {
                     
                     var ans=0;
                                var minimum_political_violence=parseInt($('#political_risk_terrorism_percentage').attr('minimum_political_violence'));
                                var political_violence_multiplier=$('#political_risk_terrorism_percentage').attr('political_violence_multiplier');
                              
                                var vehicle_value=$('#vehicle_value').val();
                                 var number_of_passengers=$('#number_of_passengers').val();
                                 
				if($('#political_risk_terrorism_percentage').is(':checked') && vehicle_value!=='' && number_of_passengers!=='')
                                {
                                     var  n_is=political_violence_multiplier* vehicle_value;
                                     var  q_is=(0.45/100)* n_is;
                                     var total=q_is+n_is;
                                     
                                     ans=total<=minimum_political_violence?minimum_political_violence:total;
                                              
                                }
				
			return ans;		
                                     
                 }
                 
                 function return_the_aa_membership_motor_psv()
                 {
                     
                     var ans=0;
                                
                                
                                var vehicle_value=$('#vehicle_value').val();
                                 var number_of_passengers=$('#number_of_passengers').val();
                                 
				if($('#aa_membership').is(':checked') && vehicle_value!=='' && number_of_passengers!=='')
                                {
                                     
                                    
                                     ans=parseInt($('#aa_membership').attr('aa_constant'));
                                     
                                              
                                }
				
			return ans;
                 }
                 
             
	}//end of document ready
                
              
);

                
               