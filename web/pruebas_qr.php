<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Demo PHP</title>
    </head>
    <body>  
        <?php
            include ('phpqrcode\phpqrcode.php');
            include ('FPDF\fpdf.php');
            
            ob_end_clean();
            
            QRcode::png('Esto es una prueba PHP', 'ejemplo.png',QR_ECLEVEL_H,8);
            $pdf = new FPDF();
            $pdf->SetCreator('qr');
            $pdf->SetTitle('Prueba PDF QR');
            $pdf->AddPage();
            $pdf->SetFont('Arial','',15);
            $pdf->Cell(40,10,'CON ESTE CODIGO QR RECLAMA TUS PUNTOS DE PARTICIPACION');
            $pdf->Image('ejemplo.png' , 60 ,22, 80 , 80,'PNG');
            $pdf->Output('qr.pdf','I');
        ?>
        
    </body>
</html>
