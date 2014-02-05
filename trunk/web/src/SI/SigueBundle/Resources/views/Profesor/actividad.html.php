<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php $view['slots']->start("menu_left"); ?>  
     <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
 
           <?php  ?>
          
              
                  
                    
                    
                     
              
      
    
   
<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("center"); ?>
       <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->
<div style="margin-left: 750px">                        
    <div id="actividad_asignatura" class="hiddenStructure">
        <form id="factividad_form" method="POST" action="<?php echo $view['router']->generate('si_sigue_generar_actividad_profesor' );?>">
            <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
            <span>Crear una nueva Actividad en <h3><?php echo $asignatura->getNombre();?> </h3></span>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="" />
            <label for="peso">Peso en nota final (en %):</label>
            <input type="text" style="width:40px;" id="peso" name="peso" value="" />      
            <label for="descripcion">Descripción:</label>
            <textarea cols="30" rows="4" id="descripcion" name="descripcion" value=""></textarea>

            <input type="submit" value="Crear" />
        </form>
    </div> 
    <?php foreach($resultados as $act_res):?>   
    <div id="actividad_<?php echo $act_res[0]->getNombre();?>" >
        <table border="1" style='text-align: center;'>
            
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Nota</th>
            <th>Peso(%)</th>
            <th>Nota Total</th>
            <?php foreach ($act_res as $fila) :?>
            <tr>
                <td><?php echo $fila->getIdAlumno()->getNombre(); ?></td>
                <td><?php echo $fila->getIdAlumno()->getApellidos(); ?></td>
                <td><?php echo $fila->getNota(); ?></td>
                <td><?php echo $fila->getPeso()*100; ?></td>  
                <td><?php echo $fila->getPeso()*$fila->getNota(); ?></td>    
                
            </tr>
            
            <?php foreach ($actividades as $actividad):?>
                

            <?php endforeach; ?>
            
            <?php endforeach; ?>
                                                
        </table>
        <form id="form_actividad_<?php echo $act_res[0]->getNombre();?>" method='GET' action=''>
            
            
        </form>
           
                                       
    </div>
    <?php endforeach; ?>
</div>
 <div id="Nueva_actividad">
        <form >
            <input type="submit" value="Nueva Actividad" onclick="nueva_actividad(); return false;" />
        </form>
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("menu_right"); ?> 
       
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
           
 });
 
function ocultar_todo(){
    $("#actividad_asignatura").addClass("hiddenStructure");
    $("div [id^='actividad_']").addClass("hiddenStructure");
}
           
function nueva_actividad(){
    ocultar_todo();
   
    $("#actividad_asignatura").removeClass("hiddenStructure");
}

function mostrar_actividad(id_actividad){
    ocultar_todo();
    $("#actividad_"+id_actividad).removeClass("hiddenStructure");
    
}
           
</script>
<?php $view['slots']->stop(); ?>
