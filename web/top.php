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
</head>
<body>
    <div id="top">
        
        <h1>Proyecto SIGUE</h1>
    </div>