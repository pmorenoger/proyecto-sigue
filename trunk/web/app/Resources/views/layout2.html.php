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
        }else{    
            
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

<div id="content">
    
    <div id="left" class="menu_left2 bloqueIzq cuadro">
         <?php $view['slots']->output('menu_left') ?>
    </div>
    <div id="center" class="calificaciones_horizontales bloqueCentro cuadro bloqueCentroLayout2">
         <?php $view['slots']->output('center') ?>
    </div>
    
</div>

<?php
?>
