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
                       <!-- <li> <a href=""> Perfil </a> </li>
                        <li> <a href=""> Otros </a> </li>
                       -->
                 </div>
          </div>
    </div>
    <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr()">

<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->
 <div id="codQR" style="margin-left:750px;"></div>
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

<?php if($exito==="true3"): ?>
    <div id="tooltip_exito"> 
        <p>¡Cambios guardados con éxito!</p>       
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
         <?php $param1 = ""; $param2 = ""; $selected="";?>
         <?php foreach ($as as $asignatura):?>
            <div id="asignatura_<?php echo $asignatura->getId();?>" style="margin-left:750px;" class="hiddenStructure">
                <form id="form_<?php echo $asignatura->getId();?>" method="POST" action="<?php echo $view['router']->generate('si_sigue_generar_tokens_profesor' );?>">
                    <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
                    <span>Generar TOKENS para la asignatura <h3><?php echo $asignatura->getNombre();?> </h3></span>
                    <label for="cantidad">Cantidad:</label>
                    <select id="cantidad" name="cantidad">
                        <option value="16">16</option>
                        <option value="20">20</option>
                        <option value="36">36</option>
                        <option value="100">100</option>
                    </select>  
                    <input type="submit" value="Generar" />
                </form>
            </div>   
            
             <div id="evaluacion_<?php echo $asignatura->getId();?>" style="margin-left:750px;" class="hiddenStructure">
                <form id="cambio_evaluacion_<?php echo $asignatura->getId();?>" method="POST" action="<?php echo $view['router']->generate('si_sigue_cambiar_metodo_evaluacion',array("id_asignatura" => $asignatura->getId()) );?>">
                    <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
                    <span>Método de Evaluación de <h3><?php echo $asignatura->getNombre();?> </h3></span> 
                    <h3>Opción 1: Peso fijo para cada Token   <a href="#" onclick="mostrar_info_opcion(1)" >+info </a></h3>
                    <div id="opcion1_texto" class="hiddenStructure">
                    <p>El profesor decide que cada token entregado aporta una cantidad fija hacia la calificación final de
                            participación (p.e. 0,25). Con este algoritmo, los alumnos persiguen obtener (al menos) un número
                            fijo de tokens, conocido desde el principio de la asignatura. La nota de participación tiene como
                            techo el 10 (y a partir de ahí los tokens adicionales no suben nota).<p>
                    </div>
                  
                    <p>Parámetros configurables:<br />
                             Valor de cada token</p>
                    <?php $id_eval = $asignatura->getIdeval();  
                        
                        if(is_null($id_eval)){
                           $int_eval = 0;
                        }else{
                          $int_eval =  $id_eval->getIdeval();
                        }
                    if( $int_eval === 1){
                        $selected = "checked";
                        $params = $asignatura->getParameval();
                        $paramsarr = explode("=", $params);
                        $param1 = $paramsarr[1];
                        
                    }else{
                        $selected = ""; $param1 = ""; $param2 = "";
                    }  ?>
                    <input type="text" id="valor_absoluto" name="valor_absoluto" value="<?php echo $param1; ?>"/>
                    <label for="metodo1">Elegir Opción 1 </label>
                    <input type="radio" id="metodo1" name="metodo" value="1" <?php echo $selected; ?>/>
                    
                    
                    <h3>Opción 2: Evaluación proporcional al mejor alumno   <a href="#" onclick="mostrar_info_opcion(2)" >+info </a></h3>
                    <div id="opcion2_texto" class="hiddenStructure">
                    <p>
                        En lugar de tener un valor fijo, el valor de cada token depende del número de tokens obtenido por el
                        alumno con mejores resultados, con un margen de tolerancia configurable.
                        Por ejemplo, si el alumno con más tokens tiene 20, y el profesor configura un margen de tolerancia
                        del 80%, todos los alumnos con 16 tokens o más tendrían un 10, y los alumnos con menos tokens
                        tendrían una puntuación proporcional a los 16.
                        Alternativamente, el profesor también optar por descartar las N mayores notas. Esto permite
                        compensar los casos en los que hay un número reducido de alumnos con un número
                        desproporcionado de tokens.</p>
                    </div>
                    
                     <?php if( $int_eval === 2){
                        $selected = "checked";
                        $params = $asignatura->getParameval();
                        $paramsarr = explode("##", $params);
                        $params0 = $paramsarr[0];
                        $param0 = explode("=", $params0);
                        $param1 = $param0[1];
                        
                        
                        $params0 = $paramsarr[1];
                        $param0 = explode("=", $params0);
                        $param2 = $param0[1];
                    }else{
                        $selected = ""; $param1 = ""; $param2 = "";
                    } ?>
                    <p>Parámetros configurables:</p>
                    <ul>
                        <li> Margen de tolerancia (en porcentaje)</li>
                         <input type="text" id="margen_tolerancia" name="margen_tolerancia" value="<?php echo $param1; ?>"/>
                        <li> Número de notas a descartar.</li>
                         <input type="text" id="num_notas_descartar" name="num_notas_descartar" value="<?php echo $param2; ?>"/>
                    </ul>
                    <label for="metodo2"> Elegir Opción 2</label>
                    <input type="radio" id="metodo2" name="metodo" value="2" <?php echo $selected; ?>/>
                         
                         
                          
                   <h3> Opción 3: Evaluación proporcional al número de tokens registrados  <a href="#" onclick="mostrar_info_opcion(3)" >+info </a></h3>
                <div id="opcion3_texto" class="hiddenStructure">
                <p>Como combinación de las anteriores, se puede hacer un algoritmo que calcule las notas en función
                    del número de tokens registrados. Si se escoge este método, se calcula el número total de tokens
                    registrados y se divide entre el número de alumnos con al menos N tokens. Esto nos da el número
                    de tokens necesarios para conseguir una puntuación X, y el resto de puntuaciones se calculan
                    proporcionalmente. </p>    
                          </div>  
                   
                    <?php if( $int_eval === 3){
                        $selected = "checked";
                        $params = $asignatura->getParameval();
                        $paramsarr = explode("##", $params);
                        $params0 = $paramsarr[0];
                        $param0 = explode("=", $params0);
                        $param1 = $param0[1];
                        
                        
                        $params0 = $paramsarr[1];
                        $param0 = explode("=", $params0);
                        $param2 = $param0[1];
                    }else{
                        $selected = ""; $param1 = ""; $param2 = "";
                    }  ?>
                <p> Parámetros configurables:</p>
                <ul> 
                    <li>Nota de referencia para la media de la clase (X)
                    <input type="text" id="nota_referencia" name="nota_referencia"  value="<?php echo $param1; ?>"/></li>
                    <li>Mínimo de tokens para ser contabilizado (N)
                    <input type="text" id="minimo_tokens" name="minimo_tokens"  value="<?php echo $param2; ?>"/>
                    </li>
                    
                </ul>
                     <label for="metodo3"> Elegir Opción 3</label>
                    <input type="radio" id="metodo3" name="metodo" value="3" <?php echo $selected; ?>/>
                     
                     
                    <input type="submit" value="Cambiar" />
                </form>
            </div>   
            
            

<!--
            <?php if(isset($alumnos) && !is_null($alumnos)): ?>
            <div id="stats_codigos_<?php $asignatura->getId() ;?>" <?php if ($exito!="stats_".$asignatura->getId()) : ?>class="hiddenStructure" <?php else: ?>style="margin-left:750px;" <?php endif;?>>                
                <h3><?php echo $asignatura->getNombre() ;?></h3>
                    <table border="3">
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Num. Códigos</th>
                        <?php foreach($alumnos as $alumno) :?>
                        <tr style="text-align: center">
                            <td><?php echo $alumno->getIdAlumno()->getNombre(); ?></td>
                            <td><?php echo $alumno->getIdAlumno()->getApellidos();?></td>
                            <td><?php echo $alumno->getNum();?></td>
                        </tr>

                    <?php endforeach; ?>
                    </table>
    
            </div>
            <?php endif ?>
-->
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
       
       function qr(){
            var id = '<?php echo $asignatura->getId();?>';
            if ($('#codQR').has('img').length){
                ocultar_todo();
                $("#nueva_asignatura").addClass("hiddenStructure");
                $("#codQR").removeClass('hiddenStructure');
            }else{
                var cod = '<?php if(isset($cod)){ echo $cod; } else{ echo ""; };?>';
                var url = '<?php echo "http://".$_SERVER['HTTP_HOST']. "/web/"; ?>';
                $.ajax({
                    type:"GET",
                    url: url + "vendor/generadorQR.php",
                    async: true,
                    data: {
                       codigo: cod 
                    },
                    dataType:"json",
                    success: function(data) {
                        if (data.status){
                            //$("#actividades").addClass('hiddenStructure');
                            //$('#divCambiar').addClass('hiddenStructure');
                            //$('#divCorreoAdicional').addClass('hiddenStructure');
                            ocultar_todo();
                            $("#codQR").removeClass('hiddenStructure');
                            $("#codQR").append("<img src='" + url + data.dir + "'>");
                            //$("#bActivar").attr("disabled", "disabled");
                        }else{
                            $("#bActivar").attr("disabled", "disabled");
                        }
                     }
                });
            }
        }
       
       function formulario_metodo_evaluacion(id_asignatura){       
          ocultar_todo();
          $("#evaluacion_"+id_asignatura).removeClass("hiddenStructure");
    
        
        }
       
       
        function generar_qr_pop_up(id_asignatura){               
                 ocultar_todo();
                 $("#asignatura_"+id_asignatura).removeClass("hiddenStructure");
              }
              
         
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
         
         function mostrar_info_opcion(idOpcion){
             
             $( "#opcion"+idOpcion+"_texto" ).dialog({                
                buttons: [
                  {
                    text: "OK",
                    click: function() {
                      $( this ).dialog( "close" );
                    }
                  }
                ]
              });
              
             
         }
       
</script>
<?php $view['slots']->stop(); ?>