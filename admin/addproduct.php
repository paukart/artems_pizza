<?php
session_start();
include('../src/php/db.php');
$picture_link = 'fourcheeze.jpg';
if (isset($_POST["send"])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $price = $_POST['price'];
    $uploaddir = '../src/images/';
    $picture_link = $_FILES['picture']['name'];
    $uploadfile = $uploaddir . basename($_FILES['picture']['name']);
    move_uploaded_file($_FILES['picture']['tmp_name'], $uploadfile);
    try {
        $conn->beginTransaction();
        $sql = "INSERT INTO products (product_name, product_description, price, picture_link) VALUE (:product_name, :product_description, :price, :picture_link)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_description', $product_description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':picture_link', $picture_link);
        $stmt->execute();
        $conn->commit();
    } catch (PDOException $e) {
        echo "You have an error: " . $e->getMessage() . "<br>";
        echo "On line: " . $e->getLine();
        $conn->rollBack();
    }
}
if (isset($_POST["edit"])) {
    $id_product = $_POST['id_product'];
    $edit_product_name = $_POST['edit_product_name'];
    $edit_product_description = $_POST['edit_product_description'];
    $edit_price = $_POST['edit_price'];
    $uploaddir = '../src/images/';
    $uploadfile = $uploaddir . basename($_FILES['edit_picture']['name']);
    $picture_link = $_FILES['edit_picture']['name'];
    move_uploaded_file($_FILES['edit_picture']['tmp_name'], $uploadfile);
    try {
        $conn->beginTransaction();
        if ($_FILES['edit_picture']['name'] != "") {
            $sql = "UPDATE products SET product_name = :product_name, product_description = :product_description, price = :price, picture_link = :picture_link WHERE id_product = :id_product";
        } else {
            $sql = "UPDATE products SET product_name = :product_name, product_description = :product_description, price = :price WHERE id_product = :id_product";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_name', $edit_product_name);
        $stmt->bindParam(':product_description', $edit_product_description);
        $stmt->bindParam(':price', $edit_price);
        if ($_FILES['edit_picture']['name'] != "") {
            $stmt->bindParam(':picture_link', $picture_link);
        }
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
    $id_product = $_POST['id_product'];
    try {
        $conn->beginTransaction();
        $sql = "DELETE FROM products WHERE id_product = :id_product";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_product', $id_product);
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
                            <a class="nav-link active" href="addproduct.php">Продукты</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="addpayment.php">Способы оплаты</a>
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
                <h4 class="mb-3">Добавить продукт</h4>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="product_name" class="form-label">Название</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Название, [Размер]">
                    </div>

                    <div class="col-sm-6">
                        <label for="product_description" class="form-label">Описание</label>
                        <input type="text" class="form-control" name="product_description" id="product_description" placeholder="Бекон, сыр">
                    </div>

                    <div class="col-sm-6">
                        <label for="price" class="form-label">Цена</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="200">
                    </div>

                    <div class="col-sm-6">
                        <label for="picture" class="form-label">Фотография</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                        <input type="file" class="form-control" name="picture" accept=".jpg, .jpeg, .png" id="picture">
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
                <h4 class="mb-3">Изменение информации о продукте</h4>
                <div class="row g-3">
                    <div id="select" class="col-sm-12"></div>
                    <input type="hidden" name="id_product" id="id_product">
                    <div class="col-sm-6">
                        <label for="edit_product_name" class="form-label">Название</label>
                        <input type="text" class="form-control" name="edit_product_name" id="edit_product_name" placeholder="Название, [Размер]">
                    </div>

                    <div class="col-sm-6">
                        <label for="edit_product_description" class="form-label">Описание</label>
                        <input type="text" class="form-control" name="edit_product_description" id="edit_product_description" placeholder="Бекон, сыр">
                    </div>

                    <div class="col-sm-6">
                        <label for="edit_price" class="form-label">Цена</label>
                        <input type="number" class="form-control" name="edit_price" id="edit_price" placeholder="200">
                    </div>

                    <div class="col-sm-6">
                        <label for="edit_picture" class="form-label">Фотография</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                        <input type="file" class="form-control" name="edit_picture" accept=".jpg, .jpeg, .png" id="edit_picture">
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
                url: '../src/php/view/menu.php',
                type: 'POST',
                cache: false,
                data: null,
                dataType: "json",
                success: function(result) {
                    if (result) {
                        var res =
                            '<select name="id_product" id="products" class="form-select">';
                        for (var i = 0; i < result.menu.id_product.length; i++) {
                            res += '<option data-id_product="' + result.menu.id_product[i] +
                                '" data-product_name="' + result.menu.product_name[i] +
                                '" data-product_description="' + result.menu.product_description[i] + '" data-price="' + result.menu.price[i] + '"  data-picture="' + result.menu.picture_link[i] + '" value="' +
                                parseInt(result.menu.id_product[i]) + '">' + result.menu
                                .product_name[i] + '</option>';
                        }
                        res += '</select>';
                        $("#select").append(res);
                        console.log(res);
                    }
                }
            });
            $(document).on('change', '#products', function() {
                $('#id_product').attr('value', $('#products').find(':selected').data('id_product'));
                $('#edit_product_name').attr('value', $('#products').find(':selected').data('product_name'));
                $('#edit_product_description').attr('value', $('#products').find(':selected').data('product_description'));
                $('#edit_price').attr('value', $('#products').find(':selected').data('price'));
                $('#edit_picture').attr('value', $('#products').find(':selected').data('picture'));
            });
        });
    </script>
    <footer class="my-5 pt-5 text-muted text-center text-small">
        <hr>
        <p class="mb-1">&copy; 2021 Пиццерия у Артема</p>
    </footer>
</body>

</html>