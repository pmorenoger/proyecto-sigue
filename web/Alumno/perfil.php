<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
    <meta HTTP-EQUIV="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/estilos.css"> 
    <link rel="stylesheet" type="text/css" href="../css/alumnoCSS.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.3.custom.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.3.custom.min.css">
    <!--<link rel="stylesheet" type="text/css" href="css/validationEngine.jquery.css"> --> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">     
    <!-- <script src="/scripts/jquery-1.8.2.min.js" type="text/javascript"></script> -->
    <script src="../scripts/jquery.validationEngine.js" type="text/javascript"></script>
    <script src="../scripts/jquery.validationEngine-es.js" type="text/javascript"></script>        
    <script src="../scripts/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="../scripts/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>   
    <script src="../scripts/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
    <title>SIGUE</title>
</head>
<body class="perfil">
    <div class="titles">
                <p><img src="imagenes/logo.png" ALIGN="middle"><text class="encabezado">IGUE</text> <text class="encabezado2">Alumno</text></p>
    </div>
    <div>
        <div class="encabezado3">
            <h3>BIENVENIDO A TU PERFIL</h3>
        </div>
        <!--<div class="accordionCentro">-->
            <div id="accordion-resizer" class="ui-widget-content">
                <div id="accordion">
                    <h3>Curso 2012/2013</h3>
                         <div>
                            <ul>           
                                <li> <a href=""> PLg </a> </li>
                                <li> <a href=""> EE </a> </li>
                                <li> <a href=""> IS </a> </li>
                                <li> <a href=""> IAIC </a> </li>
                            </ul>
                         </div>
                    <h3>Curso 2013/2014</h3>
                        <div>
                            <ul>           
                                <li> <a href=""> ISBC </a> </li>
                                <li> <a href=""> IGr </a> </li>
                                <li> <a href=""> SI </a> </li>
                                <li> <a href=""> PDA </a> </li>
                            </ul>
                         </div>
                  </div>
            </div>
        <!--</div>-->
        <br>
        <br>
        <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar">
    </div>
</body>

<script>
    $(document).ready(function(){
        $("#accordion").accordion({
            heightStyle: "fill"
        });
        $( "#accordion-resizer" ).resizable({
            minHeight: 140,
            minWidth: 200,
            resize: function() {
                $( "#accordion" ).accordion( "refresh" );
            }
        });
    });


</script>


