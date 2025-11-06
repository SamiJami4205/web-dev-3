<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "demo";
$dsn = "mysql:host=$host;dbname=$dbname";
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
  $pdo = new PDO("mysql:host=" . $host . ";dbname=" . $dbname, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("ERROR: Could not connect. " . $e->getMessage());
}
?>