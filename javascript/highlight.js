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