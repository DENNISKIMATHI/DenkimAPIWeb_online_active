// JavaScript Document
$(document).ready(function() 
	{
		
		var totals=[];
                var total=0;
		function format_kes(num) 
                {
                    var p = num.toFixed(2).split(".");
                    return "KES. " + p[0].split("").reverse().reduce(function(acc, num, i, orig) 
                    {
                        return  num=="-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
                    }, "") + "." + p[1];
                }
                
                        $('input:radio').click(function()//radio
			{
                           
                                $('#father_mother_children_total_is').html('');//clear
				var radio_name=$(this).attr('name');
                                //var value=$(this).attr('value');
                                var premium=parseInt($(this).attr('premium'));
                                var total_type=$(this).attr('total');
                               
                                        
                                //check if exists in arras
                                var am_i_there=totals[radio_name];
                                 
                                
                                
                                switch (total_type)
                                {
                                    case "father":
                                    case "mother":
                                            if(am_i_there>=0)//exists i.e was clicked
                                            {
                                                //alert('there: '+am_i_there);
                                                total=total-am_i_there;//take out the premium
                                                total=total+premium;//add to premium
                                                totals[radio_name]=premium;//add 
                                            }
                                            else//does not exist
                                            {

                                                //alert('undefined: '+am_i_there);
                                                total=total+premium;//add to premium
                                                totals[radio_name]=premium;//add 
                                            }
                                    break;
                                    
                                    case "children":
                                            var text_name= 'input_'+radio_name;
                                            var number_of_children=parseInt($('#'+text_name).val());
                                            
                                            number_of_children=number_of_children>0 ? number_of_children : 0;//if empty make zero
                                            
                                            var multiple=premium*number_of_children;
                                            
                                            //alert(number_of_children);
                                            if(am_i_there>=0)//exists i.e was clicked
                                            {
                                                //alert('there: '+am_i_there);
                                                total=total-am_i_there;//take out the premium
                                                total=total+multiple;//add to premium
                                                totals[radio_name]=multiple;//add 
                                            }
                                            else//does not exist
                                            {

                                                //alert('undefined: '+am_i_there);
                                                total=total+multiple;//add to premium
                                                totals[radio_name]=multiple;//add 
                                            }
                                            
                                            //add that premium to the text input
                                             $('#'+text_name).attr('premium','');
                                             
                                            $('#'+text_name).attr('premium',premium);
                                    break;
                                    
                                    default:
                                    break
                                        
                                }
                                
                                $('#father_mother_children_total_is').html(format_kes(total) )//add
				//check if value is in totals
                                //var len=totals.length;
                                //totals.juju=90;
                                
				
			}
		);
        
        
                 $('.i_am_children_input').keyup(function()//children
			{
                                
                                $('#father_mother_children_total_is').html('');//clear
				var radio_name=$(this).attr('radio_button_name');
                                var premium=parseInt($(this).attr('premium'));
                                var total_type=$(this).attr('total');
                               
                                    //alert(premium);    
                                //check if exists in arras
                                var am_i_there=totals[radio_name];
                                 
                                
                                
                                switch (total_type)
                                {
                                    case "father":
                                    case "mother":
                                    break;
                                    
                                    case "children":
                                            var number_of_children=parseInt($(this).val());
                                            
                                            number_of_children=number_of_children>0 ? number_of_children : 0;//if empty make zero
                                            //alert('radio_name: '+radio_name+' premium: '+premium+' am_i_there: '+am_i_there+' number_of_children: '+number_of_children);
                                            
                                            var multiple=premium*number_of_children;
                                            
                                            //alert(number_of_children);
                                            if(am_i_there>=0)//exists i.e was clicked
                                            {
                                                //alert('there: '+am_i_there);
                                                total=total-am_i_there;//take out the premium
                                                total=total+multiple;//add to premium
                                                totals[radio_name]=multiple;//add 
                                            }
                                            else//does not exist
                                            {

                                                //alert('undefined: '+am_i_there);
                                                total=total+multiple;//add to premium
                                                totals[radio_name]=multiple;//add 
                                            }
                                            //add premium to text input
                                            $(this).attr('premium','');
                                            $(this).attr('premium',premium);
                                    break;
                                    
                                    default:
                                    break
                                        
                                }
                                
                                $('#father_mother_children_total_is').html(format_kes(total) )//add
				//check if value is in totals
                                //var len=totals.length;
                                //totals.juju=90;
                                
				
			}
		);
        
              
                
                 
                 
             
	}//end of document ready
                
              
);

                
               