<?php
    include ('../vendor/PHPqrcode/phpqrcode.php');

    $key = "sigue";
    $result = $_GET['codigo'];
    $res = "";
    $string = base64_decode($result);
    for($i=0; $i<strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $res .= $char;
    }
    $data = $res;
    //tratamos el código QR
    $nombre = explode("@", $data);
    $nombre = $nombre[0];
   	$nombre = 'qr'.$nombre.'.png';
    $dir =  '../web/img/'.$nombre;
    
    QRcode::png($data, $dir,QR_ECLEVEL_H,6);
    
    $json = array('status' => true, 'dir' => 'web/img/'.$nombre);
    
    echo json_encode($json);
?>
