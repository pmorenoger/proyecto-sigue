<?php
require_once "top.php";
if($options->is_desarrollo()){
    $uploaddir = 'K:/Users/loko64z/Desktop/Sistemas-Informaticos/proyecto-sigue/web/archivos/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    echo $uploadfile;
}else{
    $uploaddir = '/home/administrador/web/archivos/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    echo $uploadfile;
}
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "El archivo es válido y fue cargado exitosamente.\n";
} else {
    echo "¡Posible ataque de carga de archivos!\n";
}

/*Se ha cargado y vamos a procesarlo para pintarlo*/
$inputFileName = $uploadfile;

/**  Identify the type of $inputFileName  **/
$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
/**  Create a new Reader of the type that has been identified  **/
$objReader = PHPExcel_IOFactory::createReader($inputFileType);


$objPHPExcel = $objReader->load($inputFileName);

$objWorksheet = $objPHPExcel->getActiveSheet();

echo '<table border="2">' . "\n";
foreach ($objWorksheet->getRowIterator() as $row) {
  echo '<tr>' . "\n";
		
  $cellIterator = $row->getCellIterator();
  $cellIterator->setIterateOnlyExistingCells(true); // This loops all cells,
                                                 // iterated.
  foreach ($cellIterator as $cell) {
    echo '<td>' . $cell->getValue() . '</td>' . "\n";
  }  
  echo '</tr>' . "\n";
}
echo '</table>' . "\n";



echo 'Aquí hay más información de depurado:';
print_r($_FILES);

print "</pre>";

?>
