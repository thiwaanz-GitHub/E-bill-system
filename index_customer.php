<?php
session_start();
include 'includes/connection.php';

// Calculation unit charges
function totalPriceForUnits($resultArray)
{
    return $resultArray[0] + $resultArray[1] + $resultArray[2];
}

function totalPriceForMonth($resultArray)
{
    return totalPriceForUnits($resultArray) + $resultArray[3];
}

function calculateBill($units)
{
    $firstRangeTotal = 0;
    $secondRangeTotal = 0;
    $thirdRangeTotal = 0;
    $fixedCharge = 0;
    $unitsTmp = $units;

    // Constants
    $fixedChargesArray = array(500, 1000, 1500);
    $firstRangePrice = 20.0;
    $secondRangePrice = 35.0;
    $thirdRangePrice = 40.0;

    while ($units > 0) {

        if ($units > 30) {
            // units <= 30
            $firstRangeTotal = 30 * $firstRangePrice;
            $units -= 30;

            if ($units > 60) {
                // units > 30, units <= 60 
                $secondRangeTotal = 30 * $secondRangePrice;
                $units -= 30;

                // units > 60 (for all)
                for ($i = 1; $i <= $units; $i++) {
                    $thirdRangeTotal += $thirdRangePrice;
                    $thirdRangePrice++;
                }
                break;
            } else {
                $secondRangeTotal = $units * 35.0;
                break;
            }
        } else {
            $firstRangeTotal = $units * 20;
            break;
        }
    }

    // $totalChargeForUnits = $firstRangeTotal + $secondRangeTotal + $thirdRangeTotal;

    $units = $unitsTmp;

    if ($units < 30) {
        $fixedCharge = $fixedChargesArray[0];
    } elseif (30 < $units && $units <= 60) {
        $fixedCharge = $fixedChargesArray[1];
    } elseif ($units > 60) {
        $fixedCharge = $fixedChargesArray[2];
    }

    $resultArray = array($firstRangeTotal, $secondRangeTotal, $thirdRangeTotal, $fixedCharge);
    return $resultArray;
}


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
    <link rel="stylesheet" href="css/styles_customer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
</head>

<body class="body" id="body">

    <div class="alert-container">
        <?php
        if (isset($_SESSION['error'])) {
            echo "<div class=\"alert-message alert alert-danger alert-dismissible fade show\" role=\"alert\">
                <strong>ERROR!</strong> " . $_SESSION['error'] .
                "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                </div>";
            unset($_SESSION['error']);

        } elseif (isset($_SESSION['success'])) {
            echo "<div class=\"alert-message alert alert-success alert-dismissible fade show\" role=\"alert\">
                <strong>Success!</strong> " . $_SESSION['success'] .
                "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                </div>";
            // unset($_SESSION['success']);
        }
        ?>
    </div>

    <div class="m-4 form">
        <form class="row g-3 d-flex justify-content-center align-items-center" method="post">
            <div class="col-auto">
                <label for="acc_no" class="form-label fw-bold">Account Number</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="acc_no" name="acc_no">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary" id="btt" name="bt_search">Search</button>
            </div>
        </form>
    </div>

    <!-- Result Container -->
    <div class="container p-5 bill mt-5">
        <?php
        if (isset($_SESSION['acc_no'])) {

            $accNo = $_SESSION['acc_no'];
            $accName = $_SESSION['acc_name'];

            $sql = "SELECT * FROM meter_reading
                WHERE m_reading <= (SELECT MAX(m_reading) FROM meter_reading) 
                AND acc_no LIKE '$accNo'
                ORDER BY m_reading DESC LIMIT 2";

            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                $rowOne = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $rowTwo = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if (is_null($rowOne) || is_null($rowTwo)) {
                    $_SESSION['error'] = 'Your bill will update soon.';
                    return;
                } else {
                    ?>

                    <h2 class="text-center fw-bold text-uppercase" >Monthly Bill</h2>
                    <div class="table-responsive p-5">
                    <div class="container justify-content-center">
                        <div class="row h6">
                            <div class="col fw-bold">Account Number</div>
                            <div class="col text-end">
                                <?php echo $accNo; ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold">Customer Name </div>
                            <div class="col text-end">
                                <?php echo $accName; ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold">Last Meter Reading </div>
                            <div class="col text-end">
                                <?php echo $rowOne['m_reading']; ?>
                            </div>
                            <div class="col text-end">
                                <?php
                                echo $rowOne['date'];
                                ;
                                ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold">Previous Meter Reading </div>
                            <div class="col text-end">
                                <?php echo $rowTwo['m_reading']; ?>
                            </div>
                            <div class="col text-end">
                                <?php
                                echo $rowTwo['date'];
                                ;
                                ?>
                            </div>

                        </div>

                        <?php
                        $units = $rowOne['m_reading'] - $rowTwo['m_reading'];
                        $resultArray = calculateBill($units);
                        $totalPriceForUnits = totalPriceForUnits($resultArray);
                        $totalPriceForMonth = totalPriceForMonth($resultArray);
                        ?>
                            <table class="table table-bordered border-dark mt-4 bill-table">
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
                                            <?php echo $resultArray[0]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Second ( 30 <,=< 60) </th>
                                        <td>1000 LKR</td>
                                        <td>35 LKR</td>
                                        <td>
                                            <?php echo $resultArray[1]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Third (60 <) </th>
                                        <td>1500 LKR</td>
                                        <td>Starting from 40 LKR increase the rate by 1 LKR per each increasing unit</td>
                                        <td>
                                            <?php echo $resultArray[2]; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3"> Total Charge For Units </th>
                                        <td>
                                            <?php echo $totalPriceForUnits; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        

                        <div class="row h6 mt-4">
                            <div class="col fw-bold">Total Units For Month</div>
                            <div class="col text-end">
                                <?php echo $units; ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold">Total Charge For Units</div>
                            <div class="col text-end">
                                <?php echo $totalPriceForUnits; ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold">Fixed Charge For Month</div>
                            <div class="col text-end">
                                <?php echo $resultArray[3]; ?>
                            </div>

                        </div>
                        <div class="row h6">
                            <div class="col fw-bold">Total Charge For Month</div>
                            <div class="col text-end">
                                <?php echo $totalPriceForMonth; ?>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    

</body>
</html>