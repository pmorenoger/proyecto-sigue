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
                                        <a href="<?php echo $view['router']->generate('si_sigue_estadisticas_asignatura_profesor', array("id_asignatura" =>$asig->getId() ));?>" onclick="ver_stats_codigo(<?php echo $asig->getId();?>)">Ver estadísticas de los Códigos</a>
                                    </li>
                                    <li>
                                        <?php $asignatura_actual = 0;
                                            if(isset($subopciones)){
                                                if(!isset($asignatura)){ 
                                                                                           
                                                }else{ 
                                                    $asignatura_actual = $asignatura->getId();                                                     
                                             }                                           
                                            } 
                                        ?>
                                        <?php $enlace=""; if($asig->getId() != $asignatura_actual){$enlace = $view['router']->generate('si_sigue_calificar_profesor', array("id_asignatura" =>$asig->getId()));} ?>
                                        <a href="<?php echo $enlace ?>" onclick="mostrar_subopciones( <?php echo $asig->getId(); ?>)" >Gestionar calificaciones</a>
                                        <?php if(isset($subopciones)){$class = "";}else{$class = "hiddenStructure";} ?>
                                        <ul id="lista_subopciones_<?php echo $asig->getId();?>" class="lista_opciones list2 <?php echo $class;?>">
                                            <li>
                                            <a href="#" onclick="nueva_actividad();">Nueva Actividad </a> 
                                            </li>
                                            <li>
                                            <a href="#" id="boton_importar" >Importar/Exportar</a>
                                            </li>
                                            <li>
                                            <a href="<?php echo $view['router']->generate('si_sigue_notificar_calificaciones', array("id_asignatura" =>$asig->getId() ) );?>" id="notificar" title="Envíe una notificación a los alumnos de esta asignatura de que ha habido cambios en las calificaciones" onclick="notificar();return false;">Notificar</a>                                   
                                            </li>                                                                        
                                        </ul>            
                                        
                                    </li>    
                                     <li>
                                        <a href="<?php echo $view['router']->generate('si_sigue_metodo_evaluacion', array("id_asignatura" =>$asig->getId() ) );?>" >Método Evaluación </a> 
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
                    </ul>
                 </div>
          </div>
    </div>
    <div class="Clear"></div>
    <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr()">
  </div>
   
   <script type="text/javascript">
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
   
   </script>
   
<?php $view['slots']->stop(); ?>


