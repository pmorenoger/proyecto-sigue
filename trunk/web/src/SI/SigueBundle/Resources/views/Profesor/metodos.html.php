<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->
<div class="perfil">
 <div id="codQR"></div>
 <?php if($exito ==="true"): ?>
     <div id="tooltip_exito"> 
        <p>¡Exito!</p>
        <p>Los cambios se han guardado satisfactoriamente.</p>
    </div>
<?php endif;?>
 
<?php if($exito ==="false"): ?>
     <div id="tooltip_exito"> 
        <p>¡Lo sentimos!</p>
        <p>No se ha podido procesar la solicitud. Inténtelo de nuevo más tarde.</p>
    </div>
<?php endif;?>
 
 
   
       <form id="cambio_evaluacion_<?php echo $asignatura->getId();?>" method="POST" action="<?php echo $view['router']->generate('si_sigue_cambiar_metodo_evaluacion');?>">
          
           
           <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
           <span>Método de Evaluación de <h3><?php echo $asignatura->getNombre();?> </h3></span> 
           <div id="accordion-resizer2" class="ui-widget-content">
             <div id="accordion2">
           
           <h3>Opción 1: Peso fijo para cada Token  </h3>
           <div>
           <div id="opcion1_texto" class="hiddenStructure">
           <p>El profesor decide que cada token entregado aporta una cantidad fija hacia la calificación final de
                   participación (p.e. 0,25). Con este algoritmo, los alumnos persiguen obtener (al menos) un número
                   fijo de tokens, conocido desde el principio de la asignatura. La nota de participación tiene como
                   techo el 10 (y a partir de ahí los tokens adicionales no suben nota).<p>
           </div>
                <a href="#" onclick="mostrar_info_opcion(1)" >+info </a>
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
           </div>

           <h3>Opción 2: Evaluación proporcional al mejor alumno   </h3>
           <div>
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
               <a href="#" onclick="mostrar_info_opcion(2)" >+info </a>
           <p>Parámetros configurables:</p>
           <ul>
               <li> Margen de tolerancia (en porcentaje)</li>
                <input type="text" id="margen_tolerancia" name="margen_tolerancia" value="<?php echo $param1; ?>"/>
               <li> Número de notas a descartar.</li>
                <input type="text" id="num_notas_descartar" name="num_notas_descartar" value="<?php echo $param2; ?>"/>
           </ul>
           <label for="metodo2"> Elegir Opción 2</label>
           <input type="radio" id="metodo2" name="metodo" value="2" <?php echo $selected; ?>/>

           </div>

          <h3> Opción 3: Evaluación proporcional al número de tokens registrados </h3>
          <div>
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
               <a href="#" onclick="mostrar_info_opcion(3)" >+info </a>
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
          </div>

          
   </div>   
            
            


</div>
</div>
    <input type="submit" value="Cambiar" />
       </form>
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
            $("#accordion2").accordion({
               heightStyle: "auto"
               
           });
           $( "#accordion-resizer2" ).resizable({
               minHeight: 140,
               minWidth: 200,
               height:600,
               resize: function() {
                   $( "#accordion2" ).accordion( "refresh" );
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
       
       function qr(){
            var id = '<?php if(isset($asignatura)){ echo $asignatura->getId();}?>';
            if ($('#codQR').has('img').length){
                ocultar_todo();
                $("#nueva_asignatura").addClass("hiddenStructure");
                $("#codQR").removeClass('hiddenStructure');
            }else{
                var cod = '<?php if(isset($cod)){ echo $cod; } else{ echo ""; };?>';
                var url = '<?php echo "http://".$_SERVER['SERVER_NAME']. ""; ?>';
                $.ajax({
                    type:"GET",
                    url: url + "/generadorQR.php",
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