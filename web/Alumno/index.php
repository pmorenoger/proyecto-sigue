<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" type="text/css" href="../css/alumnoCSS.css">
        <link rel="stylesheet" type="text/css" href="../css/validationEngine.jquery.css">
        <script src="../scripts/jquery-1.8.2.min.js" type="text/javascript">
	</script>
        <script src="../scripts/jquery.validationEngine-es.js" type="text/javascript">
	</script>
        <script src="../scripts/jquery.validationEngine.js" type="text/javascript">
	</script>
        <title>SIGUE Alumno</title>
    </head>
    <body class="login">
        <div class="titles">
                <p><img src="../img/logo.png" ALIGN="middle"><text class="encabezado">IGUE</text> <text class="encabezado2">Alumno</text></p>
	</div>
	<form method="post" id="main" action="perfil.php">
            <label class="labelCorreo">Correo Electrónico:</label>
            <input type="text" name="user" placeholder="Correo Electrónico" class='validate[required,custom[emailUCM]]'>
            <p class="ejemplo">Ej: xxxx@ucm.es</p>
            <label class="labelContraseña">Contraseña:</label>
            <input type="password" name="password" placeholder="Contraseña" class="validate[required]">
            <input type="submit" value="Entrar" id="bEntrar">
            <p class="perder"><a href="#">¿Has olvidado tu contraseña?</a>     
	</form>		
        <?php
            
        ?>
    </body>
</html>

<script type="text/javascript">  
    $(document).ready(function(){
            $("#bEntrar").submit(fnOnSubmit);
            $("#main").validationEngine('attach',{
                relative: true,
                promptPosition: "bottomRight"
            });
            
            function fnOnSubmit(){
                if (!$("#bEntrar").validationEngine('validate')) return false;
                return true;
            }
    });
</script>
