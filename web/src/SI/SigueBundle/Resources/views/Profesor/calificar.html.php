<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>





<?php $view['slots']->start("center"); ?>
       <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>
<!-- Formulario para crear una nueva actividad. -->
<div>                        
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
            <input type="button" value="Cancelar" onclick="cancelar();return false;"
        </form>
    </div> 
  
    <div id="actividad_" >
        <table border="1" style='text-align: center;'>
            
            <th>Nombre</th>
            <th>Apellidos</th>
            <?php foreach($actividades as $actividad):?>
            <th><a href="#" title="<?php echo $actividad["descripcion"]; ?>"><?php echo $actividad["nombre"]; ?></a></th>
            <th>Nota</th>
            <th>Peso(%)</th>
            <?php endforeach; ?>
            <th>TOKENS</th>
            <th>Nota TOKENS</th>
            <th>Nota Total</th>
            <th>Editar</th>
            <?php $ac_nota = 0; ?>
            <?php foreach($resultados as $fila):?>
                <tr>
                    <td><?php echo $fila["alumno"]->getNombre(); ?></td>
                    <td><?php echo $fila["alumno"]->getApellidos() ; ?></td>   
                    <?php $array_actividades = $fila["actividades"];
                        foreach($array_actividades as $fila_actividad ):
                    ?>
                     <td><?php echo $fila_actividad->getNombre() ; ?></td>   
                     <td><?php echo $fila_actividad->getNota() ; ?></td>
                     <td><?php echo $fila_actividad->getPeso() ; ?></td>
                     <?php $ac_nota = $ac_nota +( $fila_actividad->getNota() *  $fila_actividad->getPeso());?>
                    <?php endforeach;
                        $codigos = $fila["codigos"];
                         $codigos = $codigos[0];
                    ?>
                     <td><?php echo $codigos->getNum() ; ?></td>
                     <td>NotaTokens</td>
                     <td><?php echo $ac_nota;?></td>
                     <td><a href="<?php echo $view['router']->generate('si_sigue_calificar_actividad_profesor', array("id_asignatura" =>$asignatura->getId(), "id_alumno" => $fila["alumno"]->getIdAlumno()));?>" title="Editar los resultados del alumno <?php echo $fila["alumno"]->getNombre() . " " . $fila["alumno"]->getApellidos() ; ?>">Editar</a></td>
                </tr>                
            <?php endforeach; ?>
          
                                                
        </table>
       
           
                                       
    </div>
   
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
function cancelar(){
     ocultar_todo();
     $("#actividad_").removeClass("hiddenStructure");
}
           
</script>
<?php $view['slots']->stop(); ?>
