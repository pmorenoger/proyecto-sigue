<?php
//Aquí debe ir todo lo referente a librerías, includes, etc...
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require_once 'ClsOptions.php';
require_once 'Excel/Classes/PHPExcel.php';
include ('PHPqrcode/phpqrcode.php');
include ('FPDF/fpdf.php');

$options = new ClsOptions();
?>

<html>

<head>
    <meta HTTP-EQUIV="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="/css/estilos.css"> 
    <link rel="stylesheet" href="/css/alumnoCSS.css">
    <link rel="stylesheet" type="text/css" href="/css/validationEngine.jquery.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">     
    <!-- <script src="/scripts/jquery-1.8.2.min.js" type="text/javascript"></script> -->
    <script src="/scripts/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="/scripts/jquery.validationEngine-es.js" type="text/javascript"></script>        
    <script src="/scripts/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="/scripts/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>   
    <script src="/scripts/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <title>SIGUE</title>
</head>
<body>
    <div id="top">
        
        <h1>Proyecto SIGUE</h1>
    </div>