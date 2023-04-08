<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Demo</title>
</head>

<body>
    <form method="POST">
        <label for="acc_no">Account No</label>
        <input type="text" name="acc_no" id="acc_no" required>
        <button type="submit" name="submit">Submit</button>
    </form>

    <?php
    echo $_SERVER['HTTP_HOST'] . "<br>";
    echo $_SERVER['REQUEST_URI'];
    if (isset($_POST['submit'])) {
        if (isset($_POST['acc_no']) && $_POST['acc_no'] != '') {
            $accNo = $_POST['acc_no'];
            $url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]api.php/?acc_no=" . $accNo;

            $client = curl_init();
            curl_setopt($client, CURLOPT_URL, $url);
            // curl_setopt($client, CURLOPT_HEADER, true);
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($client, CURLOPT_POST, false);
            // curl_setopt($client, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($client, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($client, CURLOPT_TIMEOUT, 20);
            // curl_setopt($client, CURLOPT_FOLLOWLOCATION, false);
            $response = curl_exec($client);
            // if (($response = curl_exec($client)) === false) {
            //     echo 'Curl error: ' . curl_error($client);
            //     die('111');
            // }
            curl_close($client);
            $result = json_decode($response);

            // echo $url;
            echo "<table>";
            echo "<tr><td>Account Number:</td><td>$result->acc_no</td></tr>";
            echo "<tr><td>Last Meter Reading:</td><td>$result->last_meter_reading</td></tr>";
            echo "<tr><td>Last Meter Reading Date:</td><td>$result->last_meter_reading_date</td></tr>";
            echo "<tr><td>Previous Meter Reading:</td><td>$result->previous_meter_reading</td></tr>";
            echo "<tr><td>Previous Meter Reading Date:</td><td>$result->previous_meter_reading_date</td></tr>";
            echo "</table>";
        }
    }
    ?>
</body>

</html>
