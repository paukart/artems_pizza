<?php
session_start();
include('../db.php');
if (isset($_SESSION['order'])) {
    $client_name = $_POST['client_name'];
    $client_last_name = $_POST['client_last_name'];
    $client_email = $_POST['client_email'];
    $client_phone_number = $_POST['client_phone_number'];
    $date_order = date("Y-m-d H:i:s");
    $arr = $_SESSION['order'];
    $arr2 = $_SESSION['menu'];
    $n = 100;
    $id_client = null;
    $delivery_adress = $_POST['delivery_adress'];
    $payment_type = $_POST['payments'];
    $sql = "SELECT id_client, name, last_name, phone_number, email, COUNT(*) AS n FROM clients WHERE name = :client_name AND last_name = :client_last_name AND email = :client_email AND phone_number = :client_phone_number GROUP BY id_client;";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":client_name", $client_name);
    $stmt->bindParam(":client_last_name", $client_last_name);
    $stmt->bindParam(":client_email", $client_email);
    $stmt->bindParam(":client_phone_number", $client_phone_number);
    $stmt->execute();
    $res = $stmt->FETCHALL(PDO::FETCH_ASSOC);
    if (isset($res[0]['n'])) {
        $n = $res[0]['n'];
        $id_client = $res[0]['id_client'];
    } else {
        $n = 0;
    }
    if ($n == 0) {
        try {
            $conn->beginTransaction();
            $sql = "INSERT INTO clients (name, last_name, email, phone_number) VALUES (:client_name, :client_last_name, :client_email, :client_phone_number)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":client_name", $client_name);
            $stmt->bindParam(":client_last_name", $client_last_name);
            $stmt->bindParam(":client_email", $client_email);
            $stmt->bindParam(":client_phone_number", $client_phone_number);
            $stmt->execute();
            $id_client = $conn->lastInsertId();
            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
        }
    } elseif ($n == 1) {
        try {
            $conn->beginTransaction();
            $sql = "INSERT INTO orders (payment_type, date_order, delivery_adress, id_client, active) VALUES (:payment_type, :date_order, :delivery_adress, :id_client, 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":payment_type", $payment_type);
            $stmt->bindParam(":date_order", $date_order);
            $stmt->bindParam(":delivery_adress", $delivery_adress);
            $stmt->bindParam(":id_client", $id_client);
            $stmt->execute();
            $id_order = $conn->lastInsertId();
            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
        }
        foreach ($arr as $row) {
            try {
                $conn->beginTransaction();
                $sql = "INSERT INTO ordered_products (id_product, id_order) VALUES (:id_product, :id_order)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_product", $arr2['id_product'][$row-1]);
                $stmt->bindParam(":id_order", $id_order);
                $stmt->execute();
                $conn->commit();
            } catch (PDOException $e) {
                $conn->rollBack();
            }
        }
    }
}
