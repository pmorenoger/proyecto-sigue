<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php $view['slots']->start("menu_left"); ?>  
   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
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
                                    <a href="#" onclick="">Gestionar actividades</a>                                   
                                    </li>
                                     <li>
                                    <a href="#" onclick="">Ver lista de calificaciones</a>
                                    </li>
                                     <li>
                                    <a href="#" onclick="">Exportar lista de notas</a>
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
                        <li> <a href=""> Perfil </a> </li>
                        <li> <a href=""> Otros </a> </li>
                 </div>
          </div>
    </div>
   

<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->

 <?php if($exito==="true"): ?>
    <div id="tooltip_exito"> 
        <p>¡La lista se ha cargado con éxito!</p>
        <p>En breve tendrá acceso a todos los datos del curso introducido.</p>
    </div>
<?php endif; ?>

<?php if($exito==="true2"): ?>
    <div id="tooltip_exito"> 
        <p>¡La lista de TOKENS ha sido creada!</p>
        <p>Haga click <a href="descargar_pdf/<?php echo $ruta_pdf;?>" id="ruta_pdf">aquí</a> para descargar el pdf.</p>      
    </div>
<?php endif; ?>

<?php if($exito ==="false"): ?>
     <div id="tooltip_exito"> 
        <p>¡Lo sentimos!</p>
        <p>No se ha podido procesar la solicitud. Inténtelo de nuevo más tarde.</p>
    </div>
<?php endif;?>
<div id="nueva_asignatura" style="margin-left:750px;" class="hiddenStructure">
    <form enctype="multipart/form-data" action="subir_alumno" method="POST">
        <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
        Enviar este archivo: <input name="userfile" type="file" />
        <br />
        <br />
        <label for="nombre_asignatura" >Nombre de la asignatura: </label>
        <input id="nombre_asignatura" type="text" name="nombre_asignatura" />
        <label for="curso" > Curso: </label>
            <select id="curso" name="curso" >
                <option value="2012/2013" >2012/2013</option>
                <option value="2013/2014" >2013/2014</option>
                <option value="2014/2015" >2014/2015</option>                       
            </select>
        <label for="grupo" > Grupo: </label>
        <select id="grupo" name="grupo" >
            <option value="A" >A</option>
            <option value="B" >B</option>
            <option value="C" >C</option>
            <option value="D" >D</option>
            <option value="E" >E</option>
            <option value="F" >F</option>
            
        </select>
       
        <input type="submit" value="Enviar" />
    </form>
<h3>Envíe un archivo Excel con la información de los alumnos y complete el formulario para añadir una asignatura.</h3>

</div>


<?php if (count($asignaturas)>0) :?>
    <?php /*FALTARIA HACER DINAMICOS LOS AÑOS EN LA PARTE PROFESOR*/ ?>             
    <?php foreach ($asignaturas as $as): ?>        
         <?php foreach ($as as $asignatura):?>
            <div id="asignatura_<?php echo $asignatura->getId();?>" style="margin-left:750px;" class="hiddenStructure">
                <form id="form_<?php echo $asignatura->getId();?>" method="POST" action="<?php echo $view['router']->generate('si_sigue_generar_tokens_profesor' );?>">
                    <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
                    <span>Generar TOKENS para la asignatura <h3><?php echo $asignatura->getNombre();?> </h3></span>
                    <label for="cantidad">Cantidad:</label>
                    <select id="cantidad" name="cantidad">
                        <option value="10">16</option>
                        <option value="20">20</option>
                        <option value="50">36</option>
                        <option value="100">100</option>
                    </select>  
                    <input type="submit" value="Generar" />
                </form>
            </div>   
            <?php if(!is_null($alumnos)): ?>
            <div id="stats_codigos_<?php $asignatura->getId() ;?>" <?php if ($exito!="stats_".$asignatura->getId()) : ?>class="hiddenStructure" <?php else: ?>style="margin-left:750px;" <?php endif;?>>                
                    <table boder="1">
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Num. Códigos</th>
                        <?php foreach($alumnos as $alumno) :?>
                        <tr>
                            <td><?php echo $alumno->getIdAlumno()->getNombre(); ?></td>
                            <td><?php echo $alumno->getIdAlumno()->getApellidos();?></td>
                            <td><?php echo $alumno->getNum();?></td>
                        </tr>

                    <?php endforeach; ?>
                    </table>
    
            </div>
            <?php endif ?>

         <?php endforeach; ?>                                   
    <?php endforeach; ?>
<?php endif; ?>




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
       
        function generar_qr_pop_up(id_asignatura){               
                 ocultar_todo();
                 $("#asignatura_"+id_asignatura).removeClass("hiddenStructure");
              }
         function mostrar_opciones_asignatura(id_asignatura){    
                ocultar_todo();
                $("ul [id^='lista_opciones']").addClass("hiddenStructure");
                $("#lista_opciones_"+id_asignatura).removeClass("hiddenStructure");
                $("#nueva_asignatura").addClass("hiddenStructure");         
                //console.log("Ha llegado al de la id "+id_asignatura);
                return false;
         }
         
         function ocultar_todo(){
            $("div [id^='asignatura_']").addClass("hiddenStructure");
            $("div [id^='stats_codigos']").addClass("hiddenStructure");
         }
         
         function add_asignatura(){
            ocultar_todo();          
            $("#nueva_asignatura").removeClass("hiddenStructure");             
         }
         
         function ver_stats_codigo(idAsignatura){
           //window.location = "/estadisticas_asignatura/"+idAsignatura;       
         }
         
         $("#ruta_pdf").click(function(){
             
            $("#tooltip_exito").dialog( "close" );
         });
       
</script>
<?php $view['slots']->stop(); ?>