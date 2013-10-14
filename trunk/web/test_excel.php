<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');



/** Include PHPExcel_IOFactory */
require_once 'Excel/Classes/PHPExcel/IOFactory.php';
include "top.php";




?>
<form enctype="multipart/form-data" action="test_excel2.php" method="POST">
    <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    Enviar este archivo: <input name="userfile" type="file" />
    <br />
    <input type="submit" value="Send File" />
</form>
<h3>Envíe un archivo Excel para que sea subido y procesado al servidor</h3>

<?




include "bottom.php";
?>

