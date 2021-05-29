<?php
session_start();
include('../db.php');
$sql = "SELECT * FROM clients ORDER BY id_client";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->FETCHALL(PDO::FETCH_ASSOC);
foreach ($res as $row) {
    $clients['id_client'][] = $row['id_client'];
    $clients['name'][] = $row['name'];
    $clients['last_name'][] = $row['last_name'];
    $clients['email'][] = $row['email'];
    $clients['phone_number'][] = $row['phone_number'];
}
$out = array(
    'clients' => $clients
);
header('Content-Type: text/json; charset = utf-8');
echo json_encode($out);
