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
<div id="nueva_asignatura" >
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