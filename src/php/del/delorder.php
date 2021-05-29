<?php
include('../db.php');
$id_order = $_POST['id_order'];
try {
    $conn->beginTransaction();
    $sql = "DELETE FROM orders WHERE id_order = :id_order";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_order', $id_order);
    $stmt->execute();
    $conn->commit();
} catch (PDOException $e) {
    $conn->rollBack();
}
try {
    $conn->beginTransaction();
    $sql = "DELETE FROM ordered_products WHERE id_order = :id_order";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_order', $id_order);
    $stmt->execute();
    $conn->commit();
} catch (PDOException $e) {
    $conn->rollBack();
}