// JavaScript Document\
//hover link

function keep_alive() 
{
   
    datas_is = {}
        datas_is ["url"] = "fake_url";
        datas_is ["data"] = "none=no_data";
  
  $.ajax({
            url: "../../le_functions/keep_alive.php",
            type: "POST",
            dataType: 'json',
            data: JSON.stringify(datas_is),
            cache: false,
            crossDomain: true,
            success: function(data) 
            { 
                
                //alert(JSON.stringify(data));
                alert("Your session has been kept Alive, this will happen every 24 mins.")
                
                
                
            },
      error: function (xhr, ajaxOptions, thrownError) {
        //alert(xhr.status);
        //alert(ajaxOptions);
      }
        });
}

var myVar = setInterval(keep_alive, 1440000);//24mins
//var myVar = setInterval(keep_alive, 5000);//5 secs