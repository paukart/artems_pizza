<?php
include('../db.php');
$id_order = $_POST['id_order'];
try {
    $conn->beginTransaction();
    $sql = "UPDATE orders SET active = 0 WHERE id_order = :id_order";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_order', $id_order);
    $stmt->execute();
    $conn->commit();
} catch (PDOException $e) {
    $conn->rollBack();
}