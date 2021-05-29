<?php
session_start();
include('../src/php/db.php');
if (isset($_POST["send"])) {
    $name = $_POST['name'];
    try {
        $conn->beginTransaction();
        $sql = "INSERT INTO payment_types (name) VALUE (:name)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
if (isset($_POST["edit"])) {
    $id_payment = $_POST['id_payment'];
    $edit_name = $_POST['edit_name'];
    try {
        $conn->beginTransaction();
        $sql = "UPDATE products SET product_name = :product_name, product_description = :product_description, price = :price, picture_link = :picture_link WHERE id_product = :id_product";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $edit_product_name);
        $stmt->bindParam(':product_description', $edit_product_description);
        $stmt->bindParam(':price', $edit_price);
        $stmt->bindParam(':picture_link', $picture_link);
        $stmt->bindParam(':id_product', $id_product);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
if (isset($_POST["delete"])) {
    $id_payment = $_POST['id_payment'];
    try {
        $conn->beginTransaction();
        $sql = "DELETE FROM payment_types WHERE id_payment = :id_payment";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_payment', $id_payment);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Пиццерия у Артема</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body class="mx-5">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Админ. панель</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">Заказы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addproduct.php">Продукты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="addpayment.php">Способы оплаты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addclient.php">Клиенты</a>
                        </li>
                    </ul>
                    <form action="../index.html" class="d-flex">
                        <button class="btn btn-dark rounded" type="submit">Выйти</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <form method="POST" enctype="multipart/form-data" id="sendfm">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Добавить способ оплаты</h4>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="name" class="form-label">Название</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="VISA">
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="col-md-7 col-lg-8">
                <div class="col-6">
                    <input type="submit" id="send" name="send" class="btn btn-success" value="Добавить">
                </div>
            </div>
        </form>
        <hr class="my-4">
        <form method="POST" enctype="multipart/form-data" id="editfm">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Изменение информации о способах оплаты</h4>
                <div class="row g-3">
                    <div class="col-sm-6"><label for="edit_name" class="form-label">Редактируемый способ оплаты</label><div id="select"></div></div>
                    <input type="hidden" name="id_payment" id="id_payment">
                    <div class="col-sm-6">
                        <label for="edit_name" class="form-label">Название</label>
                        <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="Название, [Размер]">
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="col-md-7 col-lg-8">
                <div class="row g-3">
                    <div class="col-sm-2">
                        <input type="submit" id="edit" name="edit" class="btn btn-success" value="Изменить">
                    </div>
                    <div class="col-sm-2">
                        <input type="submit" id="delete" name="delete" class="btn btn-danger" value="Удалить">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#select").text('');
            $.ajax({
                url: '../src/php/view/payment.php',
                type: 'POST',
                cache: false,
                data: null,
                dataType: "json",
                success: function(result) {
                    if (result) {
                        var res =
                            '<select name="id_payment" id="payments" class="form-select">';
                        for (var i = 0; i < result.payments.id_payment.length; i++) {
                            res += '<option data-id_payment="' + result.payments.id_payment[i] +
                                '" data-name="' + result.payments.name[i] +
                                '" value="' +
                                parseInt(result.payments.id_payment[i]) + '">' + result.payments
                                .name[i] + '</option>';
                        }
                        res += '</select>';
                        $("#select").append(res);
                        console.log(res);
                    }
                }
            });
            $(document).on('change', '#payments', function() {
                $('#id_payment').attr('value', $('#payments').find(':selected').data('id_payment'));
                $('#edit_name').attr('value', $('#payments').find(':selected').data('name'));
            });
        });
    </script>
    <footer class="my-5 pt-5 text-muted text-center text-small">
        <hr>
        <p class="mb-1">&copy; 2021 Пиццерия у Артема</p>
    </footer>
</body>

</html>