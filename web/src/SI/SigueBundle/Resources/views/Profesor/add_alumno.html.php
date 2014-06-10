<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>



<?php $view['slots']->start("center"); ?>
<div class="perfil">
  <div id="add_alumno">
    <h3>Añadir Alumno a <b><?php echo $asignatura->getNombre();?></b></h3>
    <p>Rellene el formulario para agregar un alumno manualmente al sistema.</p>
    <div class="formSigue">
        <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_add_alumno_guardar', array("id_asignatura"=>$asignatura->getId()));?>" method="POST">
            <div class="form-group">
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" class="Centrar form-normal form-control"/>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellidos" class="Centrar form-normal form-control"/>
                <input type="text" id="correo" name="correo" placeholder="Correo Electrónico" class="Centrar form-normal form-control"/>
            </div>
            <input type="submit" value="Guardar" class="btn-normal sigin btn btn-block">
        </form>
    </div>
    
    
</div>
<?php if($exito=== "true"): ?>
    <div id="tooltip_exito"> 
        <p>¡Alumno añadido con éxito!</p>
        <p>En breve recibirá un correo con su contraseña para utilizar la aplicación.</p>
    </div>
<?php endif; ?>

<?php if($exito=== "false"): ?>
    <div id="tooltip_exito"> 
        <p>El alumno no ha podido ser añadido.</p>        
    </div>
<?php endif; ?>
 <?php if($exito=== "false2"): ?>
    <div id="tooltip_exito"> 
        <p>El alumno no ha podido ser añadido a la asignatura porque ya forma parte de ella.</p>        
    </div>
<?php endif; ?>

</div>
   
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    
</script>
<?php $view['slots']->stop(); ?>