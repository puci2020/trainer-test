<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('vendor/autoload.php');

if (!isset($_COOKIE['token']))
    header('Location: login.php');


function isTokenValid($token)
{
    $secret_Key = '68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=';
    try {
        $decoded = JWT::decode($token, new Key($secret_Key, 'HS512'));
        $expiration_time = $decoded->exp;
        $current_time = time();
        return $expiration_time >= $current_time;
    } catch (Exception $e) {
        return false;
    }
}

if (!isTokenValid($_COOKIE['token']))
    header('Location: login.php');

if (isset($_POST['logout']) && $_POST['logout'] === '1'){
    unset($_COOKIE['token']);
    setcookie('token', '');
    header('Location: login.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <meta name="description"
          content="Knight is a beautiful Bootstrap 4 template for product landing pages." />

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css"
          rel="stylesheet">

    <!--vendors styles-->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">


    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet"
          href="css/default.css"
          id="theme-color">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 9999">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="/knight/index.php">Strona główna <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="" method="post">
            <input hidden name="logout" value="1"/>
            <button class="btn btn-outline-success my-2 my-sm-0 btn-sm" type="submit">Wyloguj</button>
        </form>
    </div>
</nav>

<section class="smart-scroll h-100 ">
    <div class="container-fluid w-50 h-100 d-flex justify-content-center align-items-center">
        elo elo
    </div>
</section>
</body>
</html>
