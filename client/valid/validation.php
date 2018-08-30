<?php 
header("Content-Type:application/json"); 

$data = json_decode(file_get_contents('php://input'), true);

        $TransactionType = $data ['TransactionType'];
        $TransID= $data ['TransID'];
        $TransTime = $data ['TransTime'];
        $TransAmount= $data ['TransAmount'];
        $BusinessShortCode = $data ['BusinessShortCode'];
        $BillRefNumber= $data ['BillRefNumber'];
        $InvoiceNumber= $data ['InvoiceNumber'];
        $OrgAccountBalance = $data ['OrgAccountBalance'];
        $ThirdPartyTransID= $data ['ThirdPartyTransID'];
        $MSISDN = $data ['MSISDN'];
		$FirstName = $data ['FirstName'];
        $MiddleName= $data ['MiddleName'];
        $LastName = $data ['LastName'];
        
        $con = mysqli_connect("localhost", "denkimin", "B834#@G#Tter#1", "denkimin_payments");
        if (!$con) 
        {
        die("Connection failed: " . mysqli_connect_error());
        }
        $query="Select * from bla bala ";
        $result = mysqli_query($con, $query);
        
        if($result){
            $resp["ResultCode"] = 0;
            $resp["ResultDesc"] = 'Validation passed successfully';
            echo json_encode($resp);
        }else{
            $resp["ResultCode"] = 0;
            $resp["ResultDesc"] = 'Validation Failed';
            echo json_encode($resp);
        }
 
?>