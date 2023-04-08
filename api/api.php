<?php
include '../includes/connection.php';
include '../models/EBill.php';

header("Content-Type:application/json");

if(isset($_GET['acc_no']) && $_GET['acc_no'] != ''){

    $accountNumber = $_GET['acc_no'];
    $sql = "SELECT * FROM meter_reading WHERE m_reading <= 
            (SELECT MAX(m_reading) FROM meter_reading) 
            AND acc_no LIKE '$accountNumber' 
            ORDER BY m_reading DESC LIMIT 2";
    $result = $con->query($sql);

    if($result->num_rows > 0){
        $rowOne = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $rowTwo = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $last_meter_reading = $rowOne['m_reading'];
        $last_meter_reading_date = $rowOne['date'];
        $previous_meter_reading = $rowTwo['m_reading'];
        $previous_meter_reading_date = $rowTwo['date'];

        $response = array(
            "acc_no" => $accountNumber,
            "last_meter_reading" => $last_meter_reading,
            "last_meter_reading_date" => $last_meter_reading_date,
            "previous_meter_reading" => $previous_meter_reading,
            "previous_meter_reading_date" => $previous_meter_reading_date
        );
        // response($accountNumber, $last_meter_reading, $last_meter_reading_date, $previous_meter_reading, $previous_meter_reading_date);
        
        http_response_code(200);
        echo json_encode($response);
    }
    else{
        http_response_code(404);
        echo json_encode("No Data Found.");
    }
    $con->close();
}

// function response($accNo, $lastMR, $lastMRDate, $previousMR, $previousMRDate) {
//     $response['acc_no'] = $accNo;
//     $response['last_meter_reading'] = $lastMR;
//     $response['last_meter_reading_date'] = $lastMRDate;
//     $response['previous_meter_reading'] = $previousMR;
//     $response['previous_meter_reading_date'] = $previousMRDate;
	
// 	$json_response = json_encode($response);
// 	echo $json_response;
// }

?>