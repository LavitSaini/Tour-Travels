<?php
session_start();

// Database connection
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

// Optional SSL settings if required
$mysqli = mysqli_init();
$mysqli->ssl_set(NULL, NULL, NULL, NULL, NULL); // Or set actual certs if provided

try {
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = new mysqli($host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Email required check
  if (empty($email)) {
    $_SESSION['toast'] = [
      'status' => 'error',
      'message' => 'Email is required for response!'
    ];
    header("Location: index.php");
    exit();
  }

    $sql = "INSERT INTO users(name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
    $stmt->execute();

    $_SESSION['toast'] = [
      'status' => 'success',
      'message' => 'We will contact you soon!'
    ];
  }

} catch (mysqli_sql_exception $e) {
  $_SESSION['toast'] = [
    'status' => 'error',
    'message' => 'Your Response already submitted!'
  ];
} finally {
  if (isset($conn)) {
    $conn->close();
  }

  // Redirect back to index.php after handling
  header("Location: index.php");
  exit();
}
?>
