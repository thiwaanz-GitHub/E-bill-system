<?php
session_start();
include 'includes/connection.php';

if (isset($_POST['bt_search'])) {

    $accNo = $_POST['acc_no'];

    $sql = "SELECT * FROM customer WHERE acc_no LIKE '$accNo'";

    $result = $con->query($sql);
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        $_SESSION['acc_no'] = $accNo;
        $_SESSION['acc_name'] = $row['cus_name'];

    } else {
        $_SESSION['error'] = "Account number does not exists";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genarate Bill</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styles_customer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</head>

<body class="body" id="body">
    <div class="container h-100 p-5 ">
        <form class="row g-3 d-flex justify-content-center align-items-center p-5" method="post">
            <div class="col-auto">
                <label for="acc_no" class="form-label fw-bold text-uppercase ">Account Number</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="acc_no" name="acc_no">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary " name="bt_search">Search</button>
            </div>
        </form>

        <?php

        if (isset($_POST['bt_search'])) {

            $acc_no = $_POST['acc_no'];

            $sql = "SELECT * FROM customer WHERE acc_no LIKE '$acc_no'";

            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {

                    $sql_2 = "SELECT * FROM meter_reading WHERE acc_no LIKE '$acc_no'";
                    $result_2 = $con->query($sql_2);

                    if ($result_2->num_rows > 0) {
                        while ($row_2 = $result_2->fetch_assoc()) {

                            $sql_previous_date = $con->query("SELECT MAX(`date`) as second_max
                            FROM meter_reading
                            WHERE `date` < (SELECT MAX(`date`) FROM meter_reading WHERE `acc_no` LIKE '$acc_no')");

                            $sql_latest_date = $con->query("SELECT MAX(`date`) as first_max
                            FROM meter_reading
                            WHERE `acc_no` LIKE '$acc_no'");

                            $sql_latest_reader = $con->query("SELECT MAX(`m_reading`) as first_m_reading
                            FROM meter_reading
                            WHERE `acc_no` LIKE '$acc_no'");

                            $sql_previous_reader = $con->query("SELECT MAX(`m_reading`) as second_m_reading
                            FROM meter_reading
                            WHERE `m_reading` < (SELECT MAX(m_reading) FROM meter_reading WHERE `acc_no` LIKE '$acc_no')");

                            $result_previous_date = $sql_previous_date->fetch_assoc();
                            $result_latest_date = $sql_latest_date->fetch_assoc();
                            $result_latest_reader = $sql_latest_reader->fetch_assoc();
                            $result_previous_reader = $sql_previous_reader->fetch_assoc();
                        }

                            ?>

                            <div class="container p-5 bill">
                                <h2 class="text-center fw-bold text-uppercase ">Monthly Bill</h2>

                                <div class="container p-5 mt-5 justify-content-center">
                                    <div class="row h5">
                                        <div class="col fw-bold">Account Number</div>
                                        <div class="col">
                                            <?php echo $row['acc_no']; ?>
                                        </div>
                                    </div>

                                    <div class="row h5">
                                        <div class="col fw-bold">Customer Name </div>
                                        <div class="col">
                                            <?php echo $row['cus_name']; ?> 
                                        </div>
                                    </div>

                                    <div class="row h5">
                                        <div class="col fw-bold">Last Meter Reading </div>
                                        <div class="col"> <?php echo $result_latest_reader['first_m_reading']; ?></div>
                                        
                                        <?php 
                                        echo $result_latest_date['first_max'];
                                         ?>
                                        </div>
                                    
                                    <div class="row h5">
                                        <div class="col fw-bold">Previous Meter Reading </div>
                                        <div class="col "> <?php echo $result_previous_reader['second_m_reading']; ?></div>
                                        <?php 
                                        echo $result_previous_date['second_max'];
                                         ?>
                                        
                                        </div>
                                   
                                <div class="table-responsive p-5">
                                    <table class="table p-5">
                                        <thead>
                                            <tr>
                                                <th scope="col">Unit Range</th>
                                                <th scope="col">Fixed Charge</th>
                                                <th scope="col">Per Unit Charge</th>
                                                <th scope="col">Charge For Units</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            <tr>
                                                <th scope="row">First ( 30 >=)</th>
                                                <td>500 LKR</td>
                                                <td>20 LKR</td>
                                                <td>
                                                    <?php ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Second ( 30 <,=< 90) </th>
                                                <td>1000 LKR</td>
                                                <td>35 LKR</td>
                                                <td>
                                                    <?php  ?>
                                                <td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Third (60 <) </th>
                                                <td>1500 LKR</td>
                                                <td>Starting from 40 LKR increase the rate by 1 LKR per each increasing unit</td>
                                                <td>
                                                    <?php ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="3"> Total Charge For Units </th>
                                                <td>
                                                    <?php ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    <div class="row h5">
                                        <div class="col fw-bold">Total Charge For Units</div>
                                        <div class="col">
                                            <?php ?>
                                        </div>
                                    </div>

                                    <div class="row h5">
                                        <div class="col fw-bold">Fixed Charge For Month</div>
                                        <div class="col">
                                            <?php ?>
                                        </div>

                                    </div>
                                    <div class="row h5">
                                        <div class="col fw-bold">Total Charge For Month</div>
                                        <div class="col">
                                            <?php ?>
                                        </div>
                                    </div>

                                            </div>
                                            
                                </div>


                            
                            </div>
                                </div>

                                    
                            <?php

                        }
                    }
                }


// Calculation unit charges

$units = ($result_latest_reader['first_m_reading'] - $result_previous_reader['second_m_reading']); 
function calculateFirstRange($units){
    $fixedCharge = 500;
    $perUnitCharge = 20;
    $firstRangeTotal = $fixedCharge + $perUnitCharge * $units;
    return $firstRangeTotal;
}

function calculateSecondRange($units){
    $fixedCharge = 1000;
    $perUnitCharge = 35;
    $fExtraUnits = ($units - 30);
    $secondRangeTotal = $fixedCharge + $perUnitCharge * $fExtraUnits;
    return $secondRangeTotal;
}

function calculateThirdRange($units){
    $fixedCharge = 1500;
    $perUnitCharge = 40;
    $sExtraUnits = ($units - 60);
    $unitCharge = 0;
    for ($i = 1; $i <= $sExtraUnits; $i++) {
        $unitCharge += $perUnitCharge;
        $perUnitCharge++;
    }
    $thirdRangeTotal = $fixedCharge + $unitCharge;
    return $thirdRangeTotal;
}

function total($firstRangeTotal, $secondRangeTotal, $thirdRangeTotal){
    $totalCharge = $firstRangeTotal + $secondRangeTotal + $thirdRangeTotal;
    return $totalCharge;
}

function calculateBill($units) {
    $firstRangeTotal = 0;
    $secondRangeTotal = 0;
    $thirdRangeTotal = 0;
    $totalCharge = 0;

    if ($units <= 30) {
        $firstRangeTotal = calculateFirstRange($units);
    } elseif (30 < $units && $units <= 90) {
        $secondRangeTotal = (calculateFirstRange($units) + calculateSecondRange($units)) - 500;
    } elseif ($units > 60) {
        $thirdRangeTotal = (calculateFirstRange($units) + calculateSecondRange($units) + calculateThirdRange($units)) - 1500;
    }

    $totalCharge = total($firstRangeTotal, $secondRangeTotal, $thirdRangeTotal);
    return $totalCharge;
}

// Example usage
echo calculateBill($units);
//Test
// echo calculateBill(40);


  ?>
<?php
            } else {
                // echo "0 results";
            }
        ?>

</body>

</html>
