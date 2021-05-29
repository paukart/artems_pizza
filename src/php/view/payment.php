<?php
session_start();
include('../db.php');
$sql = "SELECT * FROM payment_types ORDER BY id_payment";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->FETCHALL(PDO::FETCH_ASSOC);
foreach ($res as $row) {
    $payments['id_payment'][] = $row['id_payment'];
    $payments['name'][] = $row['name'];
}
$out = array(
    'payments' => $payments
);
header('Content-Type: text/json; charset = utf-8');
echo json_encode($out);
