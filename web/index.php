<?php
require 'top.php';
?>
<div id="content">
    <div id="left">
        <img src="img/profe_der.jpg" alt="Profesora en la pizarra" title="Profesionales de la enseñanza"/>             
    </div>                                
    <div id="form_login">
        <div class="form_login"
            <form id="" name="" method="POST" action="login_do.php">
                <fieldset>
                    <legend>Datos de registro</legend>
                    <span>
                        <label for="user">Usuario: </label>
                    </span>
                    <input type="text" id="user" name="user" value="" title="nombre usuario" /> 
                    <br/>
                    <span>
                        <label for="pass">Contraseña: </label>
                    </span>
                    <input type="password" id="pass" name="pass" value="" title="password" />

                    <br/>
                    <input type="submit" id="entrar" name="entrar" value="Iniciar sesión" />
                    <div class="opciones">                        
                        <a href="contrasenya_olvidad.php" title="Recordar contraseña">¿Ha olvidado su contraseña?</a>
                        <br/>                       
                        <a href="registro.php" title="Regístrese">¿No tiene cuenta? Regístrese</a>                                               
                    </div>                    
                </fieldset>
            </form>    
        </div>  
    </div>
    <div id="right">
        <a href="http://www.ucm.es" title="UCM" >
            <img src="img/ucm_logo.gif" alt="Logo de la UCM" title="UCM" width="300px" />
        </a>
    </div>
</div>
<?php
include 'bottom.php';
?>