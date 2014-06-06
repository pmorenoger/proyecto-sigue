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



   
   
   
            <div id="asignatura_<?php echo $asignatura->getId();?>" >
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
            
           
 
           
            

</div>
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