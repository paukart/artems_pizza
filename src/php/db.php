<?php
$host = 'localhost';
$user = 'root';
$pwsd = 'Ssnpdoo4';
$db = 'pizza';
try {
  $conn = new PDO("mysql:localhost=$host;dbname=$db", $user, $pwsd);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "You have an error: " . $e->getMessage() . "<br>";
  echo "On line: " . $e->getLine();
}
?>