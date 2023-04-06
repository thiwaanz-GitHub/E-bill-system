<?php
include 'includes/session.php';

if (isset($_POST['bt_save'])) {

  $accNo = $_POST['acc_no'];
  $date = $_POST['date'];
  $meterReading = $_POST['m_reading'];

  $sql = "INSERT INTO meter_reading (acc_no, date, m_reading) VALUES ('$accNo' , '$date', '$meterReading')";

  try {
    if ($con->query($sql) === TRUE) {
      $_SESSION['success'] = "New meter reading added successfully";
    } else {
      $_SESSION['error'] = "Something went wrong. Please try again";
    }
  }
  // catch (Exception $e) {
  //   $_SESSION['error'] = $e->getMessage();

  // } 
  catch (mysqli_sql_exception $e) {
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
  <title>Meter Reading</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</head>

<body class="text-center body">

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
      unset($_SESSION['success']);
    }

    ?>
  </div>

  <main class="container w-100 m-auto form">
    <form method="post">
      <h2 class="mb-5 fw-bold text-uppercase">Meter Reading</h2>

      <div class="mb-3 row">
        <label for="acc_no" class="col-sm-2 col-form-label fw-bold">Account Number</label>
        <div class="col-sm-10">
          <input type="number" class="form-control mt-2" id="acc_no" name="acc_no" required>
        </div>
      </div>

      <div class="mb-3 row">
        <label for="date" class="col-sm-2 col-form-label fw-bold">Date</label>
        <div class="col-sm-10">
          <input type="date" class="form-control" id="date" name="date" required>
        </div>
      </div>

      <div class="mb-5 row">
        <label for="m_reading" class="col-sm-2 col-form-label fw-bold">Meter Reading</label>
        <div class="col-sm-10">
          <input type="number" class="form-control mt-3" id="m_reading" name="m_reading" required>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="index_customer.php">
          <button type="button" class="btn btn-light">Generate EBill</button>
        </a>
        <button type="submit" class="btn btn-primary " name="bt_save">Save</button>
      </div>

    </form>
  </main>

</body>

</html>