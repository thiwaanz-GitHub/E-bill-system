<?php
session_start();
include 'includes/connection.php';
include 'models/EBill.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles_customer.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
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

    <div class="container d-flex justify-content-center align-items-center bill m-4">
        <div class="logout">
            <a href="index.php"><i class="bi bi-box-arrow-left text-danger"></i></a>
        </div>
        <form class="row g-3 d-flex flex-grow-1 justify-content-center align-items-center" method="post">
            <div class="col-auto">
                <label for="acc_no" class="form-label fw-bold text-uppercase">Account Number</label>
            </div>
            <div class="col-auto">
                <input type="number" class="form-control" id="acc_no" name="acc_no">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btt" name="bt_search">Search</button>
            </div>
        </form>
    </div>

    <!-- Result Container -->

    <?php
    if (isset($_SESSION['acc_no'])) {


        $accNo = $_SESSION['acc_no'];
        $accName = $_SESSION['acc_name'];
        unset($_SESSION['acc_no']);
        unset($_SESSION['acc_name']);

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
                <div class="container p-5 bill mt-5">
                    <h1 class="text-center fw-bold text-uppercase">Monthly Bill</h1>
                    <div class="table-responsive p-5">
                        <div class="container justify-content-center">
                            <div class="row h6">
                                <div class="col fw-bold f-size">Account Number</div>
                                <div class="col text-end f-size">
                                    <?php echo $accNo; ?>
                                </div>
                            </div>

                            <div class="row h6">
                                <div class="col fw-bold f-size">Customer Name </div>
                                <div class="col text-end f-size">
                                    <?php echo $accName; ?>
                                </div>
                            </div>

                            <div class="row h6">
                                <div class="col fw-bold f-size">Last Meter Reading </div>
                                <div class="col text-end f-size">
                                    <span class="text-secondary pe-4">
                                        <?php echo $rowOne['date']; ?>
                                    </span>
                                </div>
                                <div class="col text-end f-size">
                                    <span class="">
                                        <?php echo $rowOne['m_reading']; ?>
                                    </span>
                                </div>
                            </div>



                            <div class="row h6">
                                <div class="col fw-bold f-size">Previous Meter Reading </div>
                                <div class="col text-end f-size">
                                    <span class="text-secondary pe-4">
                                        <?php echo $rowTwo['date']; ?>
                                    </span>
                                </div>
                                <div class="col text-end f-size">
                                    <span class="">
                                        <?php echo $rowTwo['m_reading']; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php
                        $myBill = new EBill($accNo, $rowOne['m_reading'], $rowOne['date'], $rowTwo['m_reading'], $rowTwo['date']);
                        $units = $myBill->units;
                        // $totalPriceForUnits = $myBill->getTotalPriceForUnits();
                        // $totalPriceForMonth = $myBill->getTotalPriceForMonth();
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
                                        <?php echo $myBill->totalFirstRange; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Second ( 30 <,=< 60) </th>
                                    <td>1000 LKR</td>
                                    <td>35 LKR</td>
                                    <td>
                                        <?php echo $myBill->totalSecondRange; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Third (60 <) </th>
                                    <td>1500 LKR</td>
                                    <td>Starting from 40 LKR increase the rate by 1 LKR per each increasing unit</td>
                                    <td>
                                        <?php echo $myBill->totalThirdRange; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3"> Total Charge For Units </th>
                                    <td>
                                        <?php echo $myBill->getTotalPriceForUnits() ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        <div class="row h6 mt-4">
                            <div class="col fw-bold f-size">Total Units For Month</div>
                            <div class="col text-end f-size">
                                <?php echo $myBill->units; ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold f-size">Total Charge For Units</div>
                            <div class="col text-end f-size">
                                <?php echo $myBill->getTotalPriceForUnits(); ?>
                            </div>
                        </div>

                        <div class="row h6">
                            <div class="col fw-bold f-size">Fixed Charge For Month</div>
                            <div class="col text-end f-size">
                                <?php echo $myBill->getFixedCharges(); ?>
                            </div>

                        </div>
                        <div class="row h6 mt-2">
                            <div class="col fw-bold text-danger fs-4">Total Charge For Month</div>
                            <div class="col text-end fw-bold text-danger fs-4">
                                <?php echo $myBill->getTotalPriceForMonth(); ?>
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