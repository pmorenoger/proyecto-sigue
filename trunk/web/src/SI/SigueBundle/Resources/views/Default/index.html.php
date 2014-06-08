<?php $view->extend('::layoutLogin.html.php') ?>

<?php $view['slots']->set('rol', 'Inicio'); ?>

<?php $view['slots']->start("menu_left"); ?>  
    <a href="http://www.ucm.es" title="UCM" >
        <img src="<?php echo $view['assets']->getUrl('img/ucm_logo.gif') ?>" alt="Logo de la UCM" title="UCM" width="100%"/>
    </a> 
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("center"); ?>
    <?php /*$item = $view['knp_menu']->get('SISigueBundle:Builder:mainMenu'); 
       
        echo $view['knp_menu']->render($item, array(), 'list'); */
    ?> 
<div class="login">
    <div class="mainCSS">
        <form method="post" id="main" action="login">
            <label for="user" class="labelCorreo">Correo Electrónico:</label>
            <input type="text" name="user" placeholder="Correo Electrónico" class='validate[required,custom[emailUCM]]'/>
            <p class="ejemplo">Ej: xxxx@ucm.es</p>
            <label for="password" class="labelContraseña">Contraseña:</label>
            <input type="password" name="password" placeholder="Contraseña" class="validate[required]"/>             
            <a href="<?php echo $view['router']->generate('si_sigue_recuperar');?>" title="Recuperar contraseña"> Recuperar Contraseña </a>
            <br />
            <input type="submit" value="Entrar" id="bEntrar" />
        </form>
    </div>
</div>
<?php if($error==="error"): ?>
    <div id="tooltip_error"> 
        <p>¡Error!</p>
        <p>Usuario/Contraseña incorrectos. Por favor, vuelva a intentarlo.</p>      
    </div>
<?php endif; ?>
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
         $( "#tooltip_error" ).dialog({                
                buttons: [
                  {
                    text: "OK",
                    click: function() {
                      $( this ).dialog( "close" );
                    }
                  }
                ]
              });
              
    });
</script>
<?php $view['slots']->stop(); ?>