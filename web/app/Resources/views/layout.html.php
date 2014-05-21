<?php $view->extend('::base.html.php') ?>

<div id="top">    
   <div class="titles"> 
       <?php $rol =  $view['slots']->get('rol'); 
       $logged = false;
        if($rol === "Profesor"){
            $rol = $view['router']->generate('si_sigue_perfil_profesor');
             $logged = true;
        }else if(substr($rol, 0,6)==="Alumno"){           
            $rol = $view['router']->generate('si_sigue_perfil_alumno');
             $logged = true;
        }else if($rol === "Admin"){            
            $rol = $view['router']->generate('si_sigue_admin');
             $logged = true;
        }else {            
            $rol = $view['router']->generate('si_sigue_homepage');
        }
       ?>
       <a href="<?php echo $rol;?>" title="Página de incio">
            <img src="<?php echo $view['assets']->getUrl('img/logo.png') ?>" ALIGN="middle">
            <text class="encabezado">IGUE</text> 
            <text class="encabezado2"> <?php $view['slots']->output('rol') ?></text>       
        </a>
    </div>
    <?php $logout = $view['router']->generate('si_sigue_logout'); ?>
    <?php if($logged): ?>
        <div id="logout"><a href="<?php echo $logout;?>" title="Salir de la aplicación y volver al login" >Salir</a></div>
    <?php endif; ?>
</div>


    <!--<div id="left">
         <?php $view['slots']->output('menu_left') ?>
    </div>
    <div id="center" class="<?php $view['slots']->output('tipo_clase') ?>">-->
    <div id="Bloque_Centro" class="bloqueCentro">
         <?php $view['slots']->output('center') ?>
    </div>
    <!--<div id="right">
         <?php $view['slots']->output('menu_right') ?>
    </div>-->


<?php
?>
