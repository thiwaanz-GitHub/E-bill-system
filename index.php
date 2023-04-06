<?php
include 'includes/session.php';

if (isset($_POST['bt_submit'])) {
  $userName = $_POST['user_name'];
  $userPass = $_POST['user_pass'];

  if (empty($userName) || empty($userPass)) {
    $_SESSION['error'] = "Username or Password is Empty.";
    // return;
  } else {
    $sql = "SELECT * FROM user WHERE name LIKE '$userName' AND password LIKE '$userPass'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
      $_SESSION['success'] = "Login Successful.";
      header('location: meter_reading.php');
    } else {
      $_SESSION['error'] = "Invalide Username.";
      // header('location: index.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Login</title>
  <link rel="stylesheet" href="css/index.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
  <link rel="stylesheet" href="css/styles.css" />
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
      // unset($_SESSION['success']);
    }
    ?>
  </div>

  <main class="container w-100 m-auto form">
    <form method="post">
      <h2 class="mb-5 fw-bold text-uppercase">Login As Meter Reader</h2>

      <div class="mb-3 row">
        <label for="user_name" class="col-sm-2 col-form-label fw-bold">Username</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="user_name" name="user_name" />
        </div>
      </div>

      <div class="mb-3 row">
        <label for="user_pass" class="col-sm-2 col-form-label fw-bold">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="user_pass" name="user_pass" />
        </div>
      </div>

      <button type="submit" class="btn btn-primary" name="bt_submit">Submit</button>
    </form>
  </main>

</body>

</html>