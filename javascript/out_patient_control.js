/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var father_last_selected;
var mother_last_selected;
var children_last_selected;

father_last_selected=null;
mother_last_selected=null;
children_last_selected=null;

function control_father_current_selected(radio_button_id)
{
	   
	
        
        if(father_last_selected==null || father_last_selected==radio_button_id )
        {
            father_last_selected=radio_button_id;
            //alert("current clicked: "+radio_button_id);
        }
        else
        {
            //alert("current clicked: "+radio_button_id+" last clicked: "+father_last_selected);
            $("#"+father_last_selected).prop( "checked", false );
            father_last_selected=radio_button_id;
            
        }
}

function control_mother_current_selected(radio_button_id)
{
	   
	
        
        if(mother_last_selected==null  || mother_last_selected==radio_button_id )
        {
            mother_last_selected=radio_button_id;
            //alert("current clicked: "+radio_button_id);
        }
        else
        {
            //alert("current clicked: "+radio_button_id+" last clicked: "+mother_last_selected);
            $("#"+mother_last_selected).prop( "checked", false );
            mother_last_selected=radio_button_id;
            
        }
}

function control_children_current_selected(radio_button_id)
{
	   
	
        
        if(children_last_selected==null  || children_last_selected==radio_button_id )
        {
            children_last_selected=radio_button_id;
            //alert("current clicked: "+radio_button_id);
        }
        else
        {
            //alert("current clicked: "+radio_button_id+" last clicked: "+children_last_selected);
            $("#"+children_last_selected).prop( "checked", false );
            children_last_selected=radio_button_id;
            
        }
}