<?php
    include ('../vendor/PHPqrcode/phpqrcode.php');
    
    $data = $_GET['codigo'];  
    $nombre = explode("@", $data);
    $nombre = $nombre[0];
   	$nombre = 'qr'.$nombre.'.png';
    $dir =  '../web/img/'.$nombre;
    
    QRcode::png($data, $dir,QR_ECLEVEL_H,6);
    
    $json = array('status' => true, 'dir' => 'web/img/'.$nombre);
    
    echo json_encode($json);
?>
