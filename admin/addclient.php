<?php
session_start();
include('../src/php/db.php');
if (isset($_POST["send"])) {
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    try {
        $conn->beginTransaction();
        $sql = "INSERT INTO clients (name, last_name, email, phone_number) VALUE (:name, :last_name, :email, :phone_number)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
if (isset($_POST["edit"])) {
    $id_client = $_POST['id_client'];
    $name = $_POST['edit_name'];
    $last_name = $_POST['edit_last_name'];
    $email = $_POST['edit_email'];
    $phone_number = $_POST['edit_phone_number'];
    try {
        $conn->beginTransaction();
        $sql = "UPDATE clients SET name = :name, last_name = :last_name, email = :email, phone_number = :phone_number WHERE id_client = :id_client";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_client', $id_client);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
if (isset($_POST["delete"])) {
    $id_client = $_POST['id_client'];
    try {
        $conn->beginTransaction();
        $sql = "DELETE FROM clients WHERE id_client = :id_client";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_client', $id_client);
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
                            <a class="nav-link" href="addpayment.php">Способы оплаты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="addclient.php">Клиенты</a>
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
                <h4 class="mb-3">Добавить клиента</h4>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="name" class="form-label">Имя</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Иван">
                    </div>
                    <div class="col-sm-6">
                        <label for="last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Иванов">
                    </div>
                    <div class="col-sm-6">
                        <label for="email" class="form-label">Почта</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="your@example.com">
                    </div>
                    <div class="col-sm-6">
                        <label for="phone_number" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" name="phone_number" id="phone_number" placeholder="71233456789">
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
                <h4 class="mb-3">Изменение информации о клиенте</h4>
                <div class="row g-3">
                    <div class="col-sm-12"><label for="edit_name" class="form-label">Электронная почта и телефон клиента</label>
                        <div id="select"></div>
                    </div>
                    <input type="hidden" name="id_client" id="id_client">
                    <div class="col-sm-6">
                        <label for="edit_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="Иван">
                    </div>
                    <div class="col-sm-6">
                        <label for="edit_last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" name="edit_last_name" id="edit_last_name" placeholder="Иванов">
                    </div>
                    <div class="col-sm-6">
                        <label for="edit_email" class="form-label">Почта</label>
                        <input type="email" class="form-control" name="edit_email" id="edit_email" placeholder="your@example.com">
                    </div>
                    <div class="col-sm-6">
                        <label for="edit_phone_number" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" name="edit_phone_number" id="edit_phone_number" placeholder="71233456789">
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
                url: '../src/php/view/clients.php',
                type: 'POST',
                cache: false,
                data: null,
                dataType: "json",
                success: function(result) {
                    if (result) {
                        var res =
                            '<select name="id_client" id="clients" class="form-select">';
                        for (var i = 0; i < result.clients.id_client.length; i++) {
                            res += '<option data-id_client="' + result.clients.id_client[i] +
                                '" data-name="' + result.clients.name[i] +
                                '" data-last_name="' + result.clients.last_name[i] +
                                '" data-email="' + result.clients.email[i] +
                                '" data-phone_number="' + result.clients.phone_number[i] +
                                '" value="' +
                                parseInt(result.clients.id_client[i]) + '">' + result.clients
                                .phone_number[i]+' / '+result.clients.email[i] + '</option>';
                        }
                        res += '</select>';
                        $("#select").append(res);
                        console.log(res);
                    }
                }
            });
            $(document).on('change', '#clients', function() {
                $('#id_client').attr('value', $('#clients').find(':selected').data('id_client'));
                $('#edit_name').attr('value', $('#clients').find(':selected').data('name'));
                $('#edit_last_name').attr('value', $('#clients').find(':selected').data('last_name'));
                $('#edit_email').attr('value', $('#clients').find(':selected').data('email'));
                $('#edit_phone_number').attr('value', $('#clients').find(':selected').data('phone_number'));
            });
        });
    </script>
    <footer class="my-5 pt-5 text-muted text-center text-small">
        <hr>
        <p class="mb-1">&copy; 2021 Пиццерия у Артема</p>
    </footer>
</body>

</html>