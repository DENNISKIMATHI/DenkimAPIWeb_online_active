// JavaScript Document\
//hover link
function hover_link(td_id,maxm,th_id)
{
	
	
	for(i=0;i<maxm;i++)
	{
		$('#'+td_id+i).attr('bgcolor','silver');//for table data
                
	}
	
}

function out_link(td_id,maxm,th_id)
{
	
	
	for(i=0;i<maxm;i++)
	{
	
			$('#'+td_id+i).removeAttr('bgcolor');//for table data
		
		
	}
	
}


function submit_me_as_currency(type,policy_number,id_for_select,url)
{
    var selected_currency_code=$('#'+id_for_select).val();
    
    //var url= window.location;
    window.location.replace(url+"?currency_code="+selected_currency_code+"&type="+type+"&policy_number="+policy_number);
   
}