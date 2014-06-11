<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>
<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, 'asignatura' => $asignatura )
        ); ?>
<?php $view['slots']->start("center"); ?>
    <div class="mensaje">
        <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>   
        <h3 style="margin-left:5%;"><?php echo $alumno->getNombre() . " " . $alumno->getApellidos() ; ?></h3>
     </div>
    <form id="calificar_asignatura" method="POST" action="<?php echo $view['router']->generate('si_sigue_calificar_actividad_guardar', array("id_asignatura" =>$asignatura->getId(), "id_alumno" => $alumno->getIdAlumno()) );?>" >
         <?php foreach($actividades as $actividad) : ?>          
          <fieldset>
              <legend><?php echo $actividad->getNombre(). " (".($actividad->getPeso()*100)."%)";?></legend>
             
              <input type="number" step="0.01" min="0" max="10" id="nota_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" name="nota_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" value="<?php echo $actividad->getNota();?>" placeholder="Nota" class="Centrar form-normal form-control validate[number,max[10]]"/>             
              <textarea cols="40" rows="20" id="obs_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" name="obs_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" value="" placeholder="Observaciones" class="Centrar form-text-area form-control"><?php echo $actividad->getObservaciones();?></textarea>
          </fieldset>
         <?php endforeach;?>  
        <input type="submit" name="submit" id="submit" value="Guardar" class=" left btn-20 btn-normal sigin btn btn-block" />
        <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="volver_calificador();" class="rigth btn-20 btn-normal sigin btn btn-block"/>
    </form>

<?php $view['slots']->stop(); ?>

      
<?php $view['slots']->start("javascripts"); ?>
      <script type="text/javascript">
      function volver_calificador(){
         window.history.go(-1);
          
      }
      
       $("#submit").submit(fnOnSubmit);
        $("#calificar_asignatura").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        function fnOnSubmit(){
                if (!$("#submit").validationEngine('validate')) return false;
                return true;
            }
      
      
      
      
      </script>
      
<?php $view['slots']->stop(); ?>

      
    