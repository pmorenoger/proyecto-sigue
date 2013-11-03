<?php
    include ('../vendor/PHPqrcode/phpqrcode.php');
    
    $data = $_GET['codigo'];
    
    QRcode::png($data, '../web/img/qr.png',QR_ECLEVEL_H,8);
    
    $json = array('status' => true, 'dir' => '/img/qr.png');
    
    echo json_encode($json);
?>
