<?php
    include ('../vendor/PHPqrcode/phpqrcode.php');

    QRcode::png('Esto es una prueba PHP', '../web/img/ejemplo.png',QR_ECLEVEL_H,8);
    
    echo 'ok';
?>
