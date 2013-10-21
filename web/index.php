<?php
require 'top.php';
?>
<div id="content">
    <div id="left">
        <img src="img/profe_der.jpg" alt="Profesora en la pizarra" title="Profesionales de la enseñanza"/>
    </div>
    <div class="login">
        <div id="form_login">
           <div class="form_login">
                <form method="post" id="main" action="perfil.php">
                    <label for="user" class="labelCorreo">Correo Electrónico:</label>
                    <input type="text" name="user" placeholder="Correo Electrónico" class='validate[required,custom[emailUCM]]'/>
                    <p class="ejemplo">Ej: xxxx@ucm.es</p>
                    <label for="password" class="labelContraseña">Contraseña:</label>
                    <input type="password" name="password" placeholder="Contraseña" class="validate[required]"/>
                    <input type="submit" value="Entrar" id="bEntrar" />
                    <p class="perder"><a href="#">¿Has olvidado tu contraseña?</a> </p>
                </form>
            </div>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#bEntrar").submit(fnOnSubmit);
        $("#main").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        function fnOnSubmit(){
                if (!$("#bEntrar").validationEngine('validate')) return false;
                return true;
            }
    });
</script>