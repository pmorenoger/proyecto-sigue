<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
   <?php $view['slots']->start("center"); ?>
   <div class="perfil">
       <div class="encabezado3">
            <h3>Menu del Profesor</h3> 
        </div>
       <div class="Izquierda">
   <div id="accordion-resizer" class="ui-widget-content">
         <div id="accordion">
           <?php if (count($asignaturas)>0) :?>
           <?php /*FALTARIA HACER DINAMICOS LOS AÑOS EN LA PARTE PROFESOR*/ ?>
            <h3>Curso 2013/2014 .</h3>
                 <div>
                    <ul>
                        <?php foreach ($asignaturas as $as): ?>
                        <li>
                             <?php foreach ($as as $asignatura):?>
                                <div id="bloque_asginatura_<?php echo $asignatura->getId();?>" class="bloque_asignatura" onclick="mostrar_opciones_asignatura(<?php echo $asignatura->getId();?>);"><?php echo $asignatura->getNombre();?> </div>
                                <ul id="lista_opciones_<?php echo $asignatura->getId();?>" class="hiddenStructure lista_opciones">
                                    <li>
                                        <a href="#" onclick="generar_qr_pop_up(<?php echo $asignatura->getId();?>);">Generar Códigos </a> 
                                    </li>
                                    <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_estadisticas_asignatura_profesor', array("id_asignatura" =>$asignatura->getId() ));?>" onclick="ver_stats_codigo(<?php echo $asignatura->getId();?>)">Ver estadísticas de los Códigos</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_calificar_profesor', array("id_asignatura" =>$asignatura->getId()));?>" >Gestionar calificaciones</a>                                   
                                    </li>    
                                     <li>
                                        <a href="#" onclick="formulario_metodo_evaluacion(<?php echo $asignatura->getId();?>);">Método Evaluación </a> 
                                    </li>
                                </ul>
                                
                             <?php endforeach; ?>                            
                        </li>
                        <?php endforeach; ?>
                    </ul>
                 </div>
            <?php endif; ?>
            <h3>Otras Opciones</h3>
                <div>
                    <ul>           
                        <li> <a href="#" onclick="add_asignatura(); return false;"> Añadir Asignatura </a> </li>
                        <li> <a href="<?php echo $view['router']->generate('si_sigue_add_profesor_asignatura');?>"> Añadir profesores </a> </li>
                       <!-- <li> <a href=""> Otros </a> </li>
                       -->
                 </div>
          </div>
    </div>
    </div>
       <div class="Derecha">
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
<?php if($exito === 1): ?>
    <div id="tooltip_exito"> 
        <p>¡Profesor añadido con éxito!</p>
        <p></p>
    </div>
<?php endif; ?>

<?php if($exito === 0): ?>
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
       
       
</script>
<?php $view['slots']->stop(); ?>  