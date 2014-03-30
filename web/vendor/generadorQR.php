<?php
    include ('../vendor/PHPqrcode/phpqrcode.php');
    
    //desencriptación del código
    $Key = "sigue";
    $input = $_GET['codigo'];
    $data = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($Key), base64_decode($input), MCRYPT_MODE_CBC, md5(md5($Key))), "\0");
    //tratamos el código QR
    $nombre = explode("@", $data);
    $nombre = $nombre[0];
   	$nombre = 'qr'.$nombre.'.png';
    $dir =  '../web/img/'.$nombre;
    
    QRcode::png($data, $dir,QR_ECLEVEL_H,6);
    
    $json = array('status' => true, 'dir' => 'web/img/'.$nombre);
    
    echo json_encode($json);
?>
