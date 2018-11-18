/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function send_combined_and_get_aggregate_totals()
{
    datas_is = {}
        datas_is ["work"] = "combined_total";
  
  var total=0;
  var payment=0;
  var balance=0;
  var credit=0;
  var show_balance=0;
    
  $.ajax({
            url: "../../le_functions/send_ajax.php",
            type: "POST",
            dataType: 'json',
            data: JSON.stringify(datas_is),
            cache: false,
            crossDomain: true,
            success: function(data) 
            { 
                
                var items = [];
                items=data;
                var check=items["check"];
                if(check===true)
                {
                    var message=[];
                                    message=items["message"];
                                    
                                    //alert(message['total']+"===="+message['payment']+"===="+message['payment']);
                                     
                                             total=message['total'];
                                             payment=message['payment'];
                                             balance=message['balance'];
                                             credit=message['credit'];
                                             show_balance=message['show_balance'];
                                       
                                                       
                                              $('#total_th').html("KES. "+total);
                                              $('#payment_th').html("KES. "+payment);
                                              $('#show_balance_th').html("KES. "+show_balance);
                                              $('#credit_th').html("KES. "+credit);
                                      
                                   
                }
                
                
            },
      error: function (xhr, ajaxOptions, thrownError) {
        //alert(xhr.status);
        //alert(ajaxOptions);
      }
      });
      
                
      
      
}