<?php
session_start();
if (isset($_POST['products'])){
$_SESSION['order'] = $_POST['products'];
}else {
    $_SESSION['order'] = null;
}