<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<?php
const NOMBRE_NEGOCIO = "OkVenta by Parzibyte";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo NOMBRE_NEGOCIO ?></title>
    <link rel="icon" type="image/png" href="./img/favicon.png"/>
    <link href="./css/animate.css" rel="stylesheet">
    <link href="./css/np.css" rel="stylesheet">
    <link href="./css/pnotify.css" rel="stylesheet">
    <link href="./css/bs/<?php echo file_get_contents("./modulos/tema.txt"); ?>.css" rel="stylesheet">
    <link href="./css/cb.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="./css/fa.css" rel="stylesheet">
    <script src="./lib/jquery.js"></script>
    <script src="./lib/bootstrap.js"></script>
    <script src="./lib/pnotify.js"></script>
    <script src="./lib/np.js"></script>
    <script src="./js/main.js"></script>
    <meta name="theme-color" content="#009688"/>
</head>
<body>
<div class="container-fluid">