<?php $view['slots']->start("menu_left"); ?>  
   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
   <div class="perfil">
    <div id="accordion-resizer" class="ui-widget-content">
         <div id="accordion">
          
            <h3>Curso 2013/2014 .</h3>
            <div>
            <?php if(isset($asignaturas)) : ?>
           <?php if (count($asignaturas)>0) :?>
           <?php /*FALTARIA HACER DINAMICOS LOS AÑOS EN LA PARTE PROFESOR*/ ?>
                 
                    <ul class="list1">
                        <?php foreach ($asignaturas as $as): ?>
                        <li>
                             <?php foreach ($as as $asig):?>
                                <div id="bloque_asginatura_<?php echo $asig->getId();?>" class="bloque_asignatura" onclick="mostrar_opciones_asignatura(<?php echo $asig->getId();?>);"><?php echo $asig->getNombre();?> </div>
                                <ul id="lista_opciones_<?php echo $asig->getId();?>" class="hiddenStructure lista_opciones list2">
                                    <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_generar_tokens', array("id_asignatura" =>$asig->getId() ));?>" onclick="">Generar Códigos </a> 
                                    </li>
                                    <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_estadisticas_asignatura_profesor', array("id_asignatura" =>$asig->getId() ));?>" >Ver estadísticas de los Códigos</a>
                                    </li>
                                    <li>
                                        <?php $asignatura_actual = 0;
                                          if(!isset($asignatura)){ 
                                               $asignatura_actual = 0;   
                                                                                                                                                                                      
                                                }else{ 
                                                    $asignatura_actual = $asignatura->getId();                                                     
                                                                                        
                                            } 
                                        ?>
                                        <?php $enlace = $view['router']->generate('si_sigue_calificar_profesor', array("id_asignatura" =>$asig->getId())); ?>
                                        <a href="<?php echo $enlace; ?>" onclick="mostrar_subopciones( <?php echo $asig->getId(); ?>)" >Gestionar calificaciones</a>
                                        <?php if(isset($subopciones)){$class = "";}else{$class = "hiddenStructure";} ?>
                                        <ul id="lista_subopciones_<?php echo $asig->getId();?>" class="lista_opciones list3 <?php echo $class;?>">
                                            <li>
                                            <a href="javascript:void(0);" onclick="nueva_actividad();">Nueva Actividad </a> 
                                            </li>
                                            <li>
                                            <a href="javascript:void(0);" id="boton_importar_<?php echo $asig->getId();?>" >Importar/Exportar</a>
                                            </li>
                                            <li>
                                            <a href="<?php echo $view['router']->generate('si_sigue_notificar_calificaciones', array("id_asignatura" =>$asig->getId() ) );?>" id="notificar" title="Envíe una notificación a los alumnos de esta asignatura de que ha habido cambios en las calificaciones" onclick="notificar();return false;">Notificar</a>                                   
                                            </li>                                                                        
                                        </ul>            
                                        
                                    </li>    
                                     <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_metodo_evaluacion', array("id_asignatura" =>$asig->getId() ) );?>" >Método Evaluación </a> 
                                    </li>
                                     <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_add_alumno', array("id_asignatura" =>$asig->getId() ) );?>" >Añadir Alumno </a> 
                                    </li>
                                </ul>
                                
                             <?php endforeach; ?>                            
                        </li>
                        <?php endforeach; ?>
                    </ul>
                
            <?php endif; ?>
            <?php endif; ?>
                 </div>
            <h3>Otras Opciones</h3>
                <div>
                    <ul class="list1">           
                         <li> <a href="<?php echo $view['router']->generate('si_sigue_add_asignatura');?>" > Añadir Asignatura </a> </li>
                        <li> <a href="<?php echo $view['router']->generate('si_sigue_add_profesor_asignatura', array("exito"=> "no"));?>"> Añadir profesores </a> </li>
                         <li> <a href="<?php echo $view['router']->generate('si_sigue_cambiar_password', array("exito"=> "no"));?>"> Cambiar Contraseña </a> </li>
                    </ul>
                 </div>
          </div>
    </div>
    <div class="Clear"></div>
    <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr();">
  </div>
   <script type="text/javascript">
    $(document).ready(function(){
        if (navigator.userAgent.indexOf('Firefox') === -1){
            $(".labelSigue").addClass("hiddenStructure");
        } 
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
           mostrar_opciones_asignatura(<?php echo $asignatura_actual;?>);
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
       
   </script>
   <script type="text/javascript">
    function qr(){
        var url = window.location.pathname;      
        var path_array = url.split('/');
        if(path_array[path_array.length-1] != "activar_app" ){
            window.location = "<?php echo $view['router']->generate('si_sigue_activar_app');?> ";
        }
      
        }
    function mostrar_opciones_asignatura(id_asignatura){    
               // ocultar_todo();
                $("ul [id^='lista_opciones']").addClass("hiddenStructure");               
                $("#lista_opciones_"+id_asignatura).removeClass("hiddenStructure");                                   
                return false;
         }
         
         function ocultar_todo(){
            $("div [id^='asignatura_']").addClass("hiddenStructure");
            $("div [id^='evaluacion_']").addClass("hiddenStructure");
            $("div [id^='evaluacion_']").addClass("hiddenStructure");
            $("div [id^='stats_codigos']").addClass("hiddenStructure");            
         }
         
   </script>
   
<?php $view['slots']->stop(); ?>


