<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Inicio'); ?>

<?php $view['slots']->start("menu_left"); ?>  
    <img src="<?php echo $view['assets']->getUrl('img/profe_der.jpg') ?>" alt="Profesora en la pizarra" title="Profesionales de la enseñanza"/>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("menu_right"); ?> 
        <a href="http://www.ucm.es" title="UCM" >
            <img src="<?php echo $view['assets']->getUrl('img/ucm_logo.gif') ?>" alt="Logo de la UCM" title="UCM" width="300px" />
        </a>   
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("center"); ?>
<div class="login">
    <div id="form_login">
        <div class="form_login">
            <form method="post" id="main" action="login">
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
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
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
<?php $view['slots']->stop(); ?>