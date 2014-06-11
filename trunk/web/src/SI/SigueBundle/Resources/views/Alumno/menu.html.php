<?php $view['slots']->start("menu_left"); ?>
    <div class="perfil">
        <div id="accordion-resizer" class="ui-widget-content">
            <div id="accordion">
                <?php if (count($asignaturas)>0) :?>
                <h3>Curso 2013/2014 .</h3>
                     <div>
                        <ul class="list1">
                            <?php foreach ($asignaturas as $as): ?>
                            <li><a href="javascript:void(0);" onclick="mostrarMenuAsignatura(<?php echo $as->getId(); ?>);"><?php echo $as->getNombre();?></a> </li>
                                 <ul id="opciones_<?php echo $as->getId(); ?>" class="hiddenStructure list2">
                                    <li><a href="javascript:void(0);" onclick="mostrarRegistrar(<?php echo $as->getId(); ?>);">Registrar un token</a></li>
                                    <li><a id="li_est_<?php echo $as->getId(); ?>" href="<?php echo $view['router']->generate('si_sigue_alumno_estadisticas', array('id' => $alumno->getIdalumno(),'asig'=>$as->getId()),true); ?>"> Mis Estadísticas </a></li>
                                </ul>    
                            <?php endforeach; ?>
                        </ul>
                     </div>
                <?php endif; ?>
                <h3>Editar Perfil</h3>
                    <div>
                        <ul class="list1">           
                            <li> <a href="javascript:void(0);" onclick="mostrarFormPassword();"> Cambiar Contraseña</a> </li>
                            <li> <a href="javascript:void(0);" onclick="mostrarFormCorreo();"> Añadir un correo adicional </a> </li>
                        </ul>
                     </div>
                <h3>Otras Opciones</h3>
                    <div>
                        <ul class="list1">           
                            <li> <a href="javascript:void(0);" onclick="mostrarActividades();"> Mostrar Actividades </a> </li>
                        </ul>
                     </div>
            </div>
        </div> 
        <div class="Clear"></div>
        <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr()">
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
              
        $("#bCambiar").submit(function(event){
            if (!$("#bCambiar").validationEngine('validate')) return false;
            return true;
        });
        $("#mainCambiar").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        
        $("#bCorreoAdicional").submit(function(evvent){
            if (!$("#bCorreoAdicional").validationEngine('validate')) return false;
            return true;
        });
        $("#mainCorreoAdicional").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
    
        <?php if (count($asignaturas)>0) :?>
            <?php foreach ($asignaturas as $as): ?>
                var id = '<?php echo $as->getId(); ?>';
                $("#bRegistrar_"+id).submit(function(event){
                    if (!$("#bRegistrar_"+id).validationEngine('validate')) return false;
                    return true;
                });
                $("#registrar_token_"+id).validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
            <?php endforeach; ?>
        <?php endif;?>
    });
    
    function qr(){
        var url = window.location.pathname;      
        var path_array = url.split('/');
        if(path_array[path_array.length-1] !== "activar_app" ){
            mostrarQR();
            window.location = "<?php echo $view['router']->generate('si_sigue_alumno_activar_app');?> ";
        }else{
            mostrarQR();
        }
    }
    
    function fnVerificar(field, rules, i, options){
        if ($("#nueva_clave").val() !== $("#verificar").val())
            return "*Las contraseñas no coinciden.";
    }
    
    function mostrarQR(){
        $("#actividades").addClass('hiddenStructure');
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $("#codQR").removeClass('hiddenStructure');
    }
    
    function mostrarActividades(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $("#divInicio").addClass("hiddenStructure");
        $('#actividades').removeClass('hiddenStructure');
    }
    
    function mostrarFormPassword(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $("#divInicio").addClass("hiddenStructure");
        $('#divCambiar').removeClass('hiddenStructure');
    }
    
    function mostrarFormCorreo(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $("#divInicio").addClass("hiddenStructure");
        $('#divCorreoAdicional').removeClass('hiddenStructure');
    }
    
    function mostrarMenuAsignatura(asig){
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $("#opciones_"+asig).removeClass("hiddenStructure");
    }
    
    function mostrarRegistrar(asig){
        $('#codQR').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $("#divInicio").addClass("hiddenStructure");
        $("#formRegistrar_"+asig).removeClass("hiddenStructure");
    }
    
    function mostrarEstadisticas(asig){
        $('#codQR').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure");
        $("#opciones_"+asig).removeClass("hiddenStructure");
        $("#divInicio").addClass("hiddenStructure");
        $("#estadisticasAlumnoAsignatura").removeClass("hiddenStructure");
    }
    
    function mostrarGraficaPorcentaje(){
        $("#general").addClass("hiddenStructure");
        $("#piechart_3d").removeClass("hiddenStructure");
        $("#bar_3d").addClass("hiddenStructure");
        $("#prediccion").addClass("hiddenStructure");
    }
        
    function mostrarGraficaAlumnosTokens(){
        $("#general").addClass("hiddenStructure");
        $("#prediccion").addClass("hiddenStructure");
        $("#piechart_3d").addClass("hiddenStructure");
        $("#bar_3d").removeClass("hiddenStructure");
    }
    
    function mostrarPredicciones(){
        $("#general").addClass("hiddenStructure");
        $("#piechart_3d").addClass("hiddenStructure");
        $("#bar_3d").addClass("hiddenStructure");
        $("#prediccion").removeClass("hiddenStructure");
    }
    
    function mostrarGeneral(){
        $("#general").removeClass("hiddenStructure");
        $("#piechart_3d").addClass("hiddenStructure");
        $("#bar_3d").addClass("hiddenStructure");
        $("#prediccion").addClass("hiddenStructure");
    }
</script>
<?php $view['slots']->stop(); ?>