<?php
session_start();
include('../db.php');
$sql = "SELECT orders.id_order, payment_types.name AS payment_name, date_order, delivery_adress, products.id_product, product_name, price, CONCAT(clients.name,' ', clients.last_name) as fio, clients.phone_number, orders.active FROM orders JOIN ordered_products ON ordered_products.id_order = orders.id_order JOIN products ON ordered_products.id_product = products.id_product JOIN clients ON orders.id_client = clients.id_client JOIN payment_types ON payment_type = payment_types.id_payment ORDER BY date_order DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->FETCHALL(PDO::FETCH_ASSOC);
foreach ($res as $row) {
    $orders['id_order'][] = $row['id_order'];
    $orders['payment_name'][] = $row['payment_name'];
    $orders['date_order'][] = $row['date_order'];
    $orders['delivery_adress'][] = $row['delivery_adress'];
    $orders['product_name'][] = $row['product_name'];
    $orders['price'][] = $row['price'];
    $orders['fio'][] = $row['fio'];
    $orders['phone_number'][] = $row['phone_number'];
    $orders['active'][] = $row['active'];
}
$out = array(
    'orders' => $orders
);
header('Content-Type: text/json; charset = utf-8');
echo json_encode($out);
