<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>
<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, 'asignatura' => $asignatura )
        ); ?>
<?php $view['slots']->start("center"); ?>
    <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>   
      <h3 style="margin-left:5%;"><?php echo $alumno->getNombre() . " " . $alumno->getApellidos() ; ?></h3>
      <form id="calificar_asignatura" method="POST" action="<?php echo $view['router']->generate('si_sigue_calificar_actividad_guardar', array("id_asignatura" =>$asignatura->getId(), "id_alumno" => $alumno->getIdAlumno()) );?>" >
         <?php foreach($actividades as $actividad) : ?>          
          <fieldset>
              <legend><?php echo $actividad->getNombre(). " (".$actividad->getPeso().")";?></legend>
              <label for="nota_<?php echo $actividad->getNombre();?>">Nota: </label>
              <input type="text" id="nota_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" name="nota_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" value="<?php echo $actividad->getNota();?>"/>
              <label for="obs_<?php echo $actividad->getNombre();?>">Observaciones: </label>
              <textarea cols="40" rows="20" id="obs_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" name="obs_<?php echo str_replace(" ", "%/%", $actividad->getNombre());?>" value=""><?php echo $actividad->getObservaciones();?></textarea>
          </fieldset>
         <?php endforeach;?>
          
          
          <input type="submit" name="submit" id="submit" value="Guardar" />
          <input type="button" name="cancelar" id="cancelar" value="Cancelar" onclick="volver_calificador();"/>
      </form>

<?php $view['slots']->stop(); ?>

      
<?php $view['slots']->start("javascripts"); ?>
      <script type="text/javascript">
      function volver_calificador(){
         window.history.go(-1);
          
      }
      $(document).ready(function(){
           $("#accordion").accordion({
               heightStyle: "fill"
           });
           $( "#accordion-resizer" ).resizable({
               minHeight: 140,
               minWidth: 200,
               resize: function() {
                   $( "#accordion" ).accordion( "refresh" );
               }
           });
             mostrar_opciones_asignatura(<?php echo $asignatura->getId();?>);
           $( "#tooltip_exito" ).dialog({                
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
      
      
      
      
       function mostrar_opciones_asignatura(id_asignatura){    
               // ocultar_todo();
                $("ul [id^='lista_opciones']").addClass("hiddenStructure");
                $("#codQR").addClass("hiddenStructure");
                $("#lista_opciones_"+id_asignatura).removeClass("hiddenStructure");
                $("#nueva_asignatura").addClass("hiddenStructure");         
                //console.log("Ha llegado al de la id "+id_asignatura);
                return false;
         }
         
         function ocultar_todo(){
            $("div [id^='asignatura_']").addClass("hiddenStructure");
            $("div [id^='evaluacion_']").addClass("hiddenStructure");
            $("div [id^='evaluacion_']").addClass("hiddenStructure");
            $("div [id^='stats_codigos']").addClass("hiddenStructure");
            $("#codQR").addClass("hiddenStructure");
         }
         
      </script>
      
<?php $view['slots']->stop(); ?>

      
    