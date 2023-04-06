<?php
session_start();
include 'includes/connection.php';


// if (isset($_SESSION['user'])) {
// 	// header('location: admin/home.php');
// 	// $_SESSION['user-type'] = 1;
// }
// elseif (isset($_SESSION['user'])) {
// 	try {
// 		$stmt = $conn->prepare("SELECT * FROM user WHERE user_id=:id");
// 		$stmt->execute(['id' => $_SESSION['user']]);
// 		$user = $stmt->fetch();
// 		// $_SESSION['user-type'] = 0;
// 	} catch (PDOException $e) {
// 		$_SESSION['error'] = "Invalide User: " . $e->getMessage();
// 	}

// 	// $pdo->close();
// }
// else{

// }
