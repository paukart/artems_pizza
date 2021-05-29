<?php
session_start();
include('../db.php');
$sql = "SELECT * FROM products ORDER BY id_product";
$stmt = $conn->prepare($sql);
$stmt->execute();
$res = $stmt->FETCHALL(PDO::FETCH_ASSOC);
foreach ($res as $row) {
    $menu['id_product'][] = $row['id_product'];
    $menu['product_name'][] = $row['product_name'];
    $menu['product_description'][] = $row['product_description'];
    $menu['price'][] = $row['price'];
    $menu['picture_link'][] = $row['picture_link'];
}
$_SESSION['menu'] = $menu;
$out = array(
    'menu' => $menu
);
header('Content-Type: text/json; charset = utf-8');
echo json_encode($out);
