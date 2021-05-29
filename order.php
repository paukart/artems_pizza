<?php session_start();
$arr2 = $_SESSION['menu'];
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Пиццерия у Артема</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</head>

<body class="mx-5">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Пиццерия у Артема</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.html">Главная</a>
                        </li>
                    </ul>
                    <form action="order.php" class="d-flex">
                        <button class="btn btn-success mx-4 rounded" type="submit">Корзина</button>
                    </form>
                    <form action="admin/index.html" class="d-flex">
                        <button class="btn btn-success rounded" type="submit">Админ</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="alert alert-success my-3" role="alert" id="success">Заказ сформирован!</div>
        <form method="POST" id="sendfm">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Оформление заказа</h4>
                <div class="row g-3">
                    <div class="alert alert-warning" role="alert">
                        Пожалуйста, проверьте все данные перед оформлением заказа.
                    </div>
                    <div class="col-sm-6">
                        <label for="client_name" class="form-label">Имя</label>
                        <input type="text" class="form-control" name="client_name" id="client_name" placeholder="Иван">
                    </div>

                    <div class="col-sm-6">
                        <label for="client_last_name" class="form-label">Фамилия</label>
                        <input type="text" class="form-control" name="client_last_name" id="client_last_name" placeholder="Иванов">
                    </div>

                    <div class="col-sm-6">
                        <label for="client_email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="client_email" id="client_email" placeholder="you@example.com">
                    </div>

                    <div class="col-sm-6">
                        <label for="client_phone_number" class="form-label">Телефон</label>
                        <input type="tel" class="form-control" name="client_phone_number" id="client_phone_number" placeholder="71233456789">
                    </div>

                    <div class="col-sm-6">
                        <label for="client_email" class="form-label">Адрес доставки</label>
                        <input type="email" class="form-control" name="delivery_adress" id="delivery_adress" placeholder="проспект Славы, 52, 25, 4 подъезд">
                    </div>

                    <div class="col-sm-6">
                        <label for="client_phone_number" class="form-label">Оплата</label>
                        <div id="payment" class="col-sm-12"></div>
                    </div>
                </div>
                <div class="row g-3 my-4">
                    <div id="ordered_products" class="col-12">
                        <label for="products" class="form-label fw-bold fs-4">Список позиций:</label>
                        <?php if (isset($_SESSION['order'])) {
                            $arr = $_SESSION['order'];
                            echo '<ol name="products">';
                            foreach ($arr as $row) {
                                echo '<li class="fs-5">' . $arr2['product_name'][$row-1] . '</li>';
                            }
                            echo '<ol>';
                        } else {
                            echo '<p>Вы пока ничего не выбрали :(</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="col-md-7 col-lg-8">
                <div class="row g-3 my-2">
                    <div class="col-sm-2">
                        <input type="submit" id="send" name="send" class="btn btn-success" value="Заказать">
                    </div>
                    <div class="col-sm-2">
                        <input type="reset" id="reset" name="reset" class="btn btn-danger" value="Очистить">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        $('#success').hide();
        $("#payment").text('');
        $.ajax({
            url: 'src/php/view/payment.php',
            type: 'POST',
            cache: false,
            data: null,
            dataType: "json",
            success: function(result) {
                if (result) {
                    var res =
                        '<select name="payments" id="payments" class="form-select">';
                    for (var i = 0; i < result.payments.id_payment.length; i++) {
                        res += '<option value=' + result.payments.id_payment[i] + '>' + result.payments.name[i] + '</option>';
                    }
                    res += '</select>';
                    console.log(res);
                    $("#payment").append(res);
                } else {
                    console.log("ОШИБКА!");
                }
            }
        });
        $(document).ready(function() {
            $("#send").click(function(e) {
                e.preventDefault();
                var insert_order = $("#sendfm").serialize();
                $.ajax({
                        url: 'src/php/add/addorder.php',
                        type: 'POST',
                        data: insert_order,
                        datatype: 'json'
                    })
                    .done(function() {
                        console.log("success");
                        $("#success").show("slow");
                        setTimeout(function() {
                            $("#success").hide('slow');
                        }, 2000);
                    })
                    .fail(function() {
                        console.log("error");
                    });
            });
            $("#reset").click(function(e) {
                e.preventDefault();
                $.ajax({
                        url: 'src/php/order.php',
                        type: 'POST',
                        data: null,
                        datatype: 'json'
                    })
                    .done(function() {
                        $("#ordered_products").load(location.href + " #ordered_products");
                    })
                    .fail(function() {
                        console.log("error");
                    });
            });
        });
    </script>
    <footer class="my-5 pt-5 text-muted text-center text-small">
        <hr>
        <p class="mb-1">&copy; 2021 Пиццерия у Артема</p>
    </footer>
</body>

</html>