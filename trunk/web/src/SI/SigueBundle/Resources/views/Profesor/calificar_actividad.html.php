<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php $view['slots']->start("center"); ?>
    <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>   
      <h3 style="margin-left:5%;"><?php echo $alumno->getNombre() . " " . $alumno->getApellidos() ; ?></h3>
      <form id="calificar_asignatura" method="POST" action="" >
         <?php foreach($actividades as $actividad) : ?>
          <fieldset>
              <legend><?php echo $actividad->getNombre();?></legend>
              <input type="text" id="nota_<?php echo $actividad->getId();?>" name="nota_<?php echo $actividad->getId();?>" value="<?php echo $actividad->getNota();?>">
          </fieldset>
         <?php endforeach;?>
          
          
          <input type="submit" name="submit" id="submit" value="Guardar" />
      </form>

<?php $view['slots']->stop(); ?>
    