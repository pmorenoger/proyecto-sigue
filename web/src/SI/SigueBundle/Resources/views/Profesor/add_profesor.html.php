<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

  <?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, 'asignatura' => $asignatura )
        ); ?>
   
<?php $view['slots']->start("center"); ?>
       <div class="perfil">
<h3> Menú administración </h3>
<div id="add_profesor">
    <h3>Añadir Profesor </h3>
    <p>Rellene por cada asignatura los profesores que quiera añadir</p>
    <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_add_profesor_asignatura_guardar');?>" method="POST">
        <?php foreach ($asig_prof as $pares):?>
        <fieldset> 
            <legend><?php 
                        $nombre_asignatura = $pares["asignatura"][0]->getNombre();
                        $id_asignatura = $pares["asignatura"][0]->getId();
                echo  $nombre_asignatura ; ?></legend>
            <?php 
                $arr_prof = $pares["profesores"]; ?>
            <!--<input type="hidden" id="id_asignatura_<?php echo $id_asignatura;?>" name="id_asignatura_<?php echo $id_asignatura;?>" value="<?php echo $id_asignatura;?>"> -->
            <select multiple name="idAsignatura_<?php echo $id_asignatura;?>[]">
            <?php
            foreach ($arr_prof as $profesor) :?>
                <option type="checkbox" name="profesor_<?php echo $id_asignatura?>" id="profesor_<?php echo $id_asignatura."_".$profesor->getIdprofesor(); ?>" title="<?php echo $profesor->getCorreo() ?>" value="<?php echo $profesor->getIdprofesor() ?>">
                 <?php echo $profesor->getNombre() ." ". $profesor->getApellidos() ?></option>
              
                
            <?php endforeach;?>
             </select>
        </fieldset>        
        <?php endforeach;?>
        <input type="submit" value="Guardar">
    </form>
    
    
    
</div>
<?php if($exito === "true"): ?>
    <div id="tooltip_exito"> 
        <p>¡Profesor añadido con éxito!</p>
        <p></p>
    </div>
<?php endif; ?>

<?php if($exito === "false"): ?>
    <div id="tooltip_exito"> 
        <p>¡Lo sentimos!</p>
        <p>El profesor no ha podido ser añadido a la asignatura.</p>
        
    </div>
<?php endif; ?>
       </div>
       <div class="Clear"></div>
</div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
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
       
       function ocultar_todo(){
            $("#add_profesor").addClass("hiddenStructure");
       }
       
       function add_profesor(){
            ocultar_todo();
            $("#add_profesor").removeClass("hiddenStructure");
       
       }
       $("select").multiselect({
            selectedText: "seleccionados # de #"
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