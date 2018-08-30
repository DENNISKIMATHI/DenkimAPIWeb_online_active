// JavaScript Document\
//hover link
function reload_are_you_human(id)
{
	
	$('#'+id).attr('src','');//remove
        $('#'+id).attr('src','./service/_antispam.php');//add
	
}

