<?php $view->extend('::base.html.php') ?>

<div id="top" class="bottom70">    
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
     <h1 class="encabezado">Sigue</h1>   
    <?php $logout = $view['router']->generate('si_sigue_logout'); ?>
    <?php if($logged): ?>
        <div id="logout">
            <a href="<?php echo $logout;?>" title="Salir de la aplicación y volver al login" >
                <img src="<?php echo $view['assets']->getUrl('img/logout.png') ?>" height="30px" alt="salir">
            </a>
        </div>
    <?php endif; ?>
    
</div>
<div class="Clear"></div>
    <div id="center" class="<?php $view['slots']->output('tipo_clase') ?> bloqueCentroLogin">
    <!--<div id="Bloque_Centro" class="bloqueCentro">-->
         <?php $view['slots']->output('center') ?>
    </div>
<?php
?>
