<?php $view->extend('::base.html.php') ?>

<div id="top" class="headerSigue">    
   <div id="titles"> 
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
       <div class="center">
        <a href="<?php echo $rol;?>" title="Página de incio">
            <text class="encabezadoHeader">Sigue</text>    
        </a>
        </div>
       <?php $logout = $view['router']->generate('si_sigue_logout'); ?>
    <?php if($logged): ?>
        <div id="logout">
            <a href="<?php echo $logout;?>" title="Salir de la aplicación y volver al login" >
                <p class="logoutHeader"><strong>Salir</strong></p>
            </a>
        </div>
    <?php endif; ?>
    </div>
    
</div>
<div class="Clear"></div>

    <div id="left" class="bloqueIzq cuadro">
         <?php $view['slots']->output('menu_left') ?>
    </div>
    <div id="center" class="<?php $view['slots']->output('tipo_clase') ?> bloqueCentro cuadro">
    <!--<div id="Bloque_Centro" class="bloqueCentro">-->
         <?php $view['slots']->output('center') ?>
    </div>
    <div id="right" class="bloqueDer">
         <?php $view['slots']->output('menu_right') ?>
   </div>


<?php
?>
