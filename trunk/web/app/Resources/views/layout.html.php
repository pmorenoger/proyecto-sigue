<?php $view->extend('::base.html.php') ?>

<div id="top">    
   <div class="titles"> 
       <?php $rol =  $view['slots']->get('rol'); 
        if($rol === "Profesor"){
            $rol = $view['router']->generate('si_sigue_perfil_profesor');
        }else{
            $rol = $view['router']->generate('si_sigue_perfil_alumno');
        }
       ?>
       <a href="<?php echo $rol;?>" title="Página de incio">
            <img src="<?php echo $view['assets']->getUrl('img/logo.png') ?>" ALIGN="middle">
            <text class="encabezado">IGUE</text> 
            <text class="encabezado2"> <?php $view['slots']->output('rol') ?></text>       
        </a>
    </div>
    <?php $logout = $view['router']->generate('si_sigue_logout'); ?>
    <div id="logout"><a href="<?php echo $logout;?>" title="Salir de la aplicación y volver al login" >Salir</a></div>
</div>

<div id="content">
    <div id="left">
         <?php $view['slots']->output('menu_left') ?>
    </div>
    <div id="center" class="<?php $view['slots']->output('tipo_clase') ?>">
         <?php $view['slots']->output('center') ?>
    </div>
    <div id="right">
         <?php $view['slots']->output('menu_right') ?>
    </div>
</div>

<?php
?>