<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Alumno'); ?>

<?php $view['slots']->start("menu_left"); ?>
    <div class="perfil">
        
        <!--<div style="height: auto;width: auto;">-->
                <div id="accordion-resizer" class="ui-widget-content">
                    <div id="accordion">
                        <?php if (count($asignaturas)>0) :?>
                        <h3>Curso 2013/2014 .</h3>
                             <div>
                                <ul>
                                    <?php foreach ($asignaturas as $as): ?>
                                    <li><a href="javascript:void(0);" onclick="mostrarMenuAsignatura(<?php echo $as->getId(); ?>);"><?php echo $as->getNombre();?></a> </li>
                                         <ul id="opciones_<?php echo $as->getId(); ?>" class="hiddenStructure">
                                            <li><a href="javascript:void(0);" onclick="mostrarRegistrar(<?php echo $as->getId(); ?>);">Registrar un token</a></li>
                                            <li><a id="li_est_<?php echo $as->getId(); ?>" href="<?php echo $view['router']->generate('si_sigue_alumno_estadisticas', array('id' => $alumno->getIdalumno(),'asig'=>$as->getId()),true); ?>"> Mis Estadísticas </a></li>
                                        </ul>    
                                    <?php endforeach; ?>
                                </ul>
                             </div>
                        <?php endif; ?>
                        <h3>Editar Perfil</h3>
                            <div>
                                <ul>           
                                    <li> <a href="javascript:void(0);" onclick="mostrarFormPassword();"> Cambiar Contraseña</a> </li>
                                    <li> <a href="javascript:void(0);" onclick="mostrarFormCorreo();"> Añadir un correo adicional </a> </li>
                                </ul>
                             </div>
                        <h3>Otras Opciones</h3>
                            <div>
                                <ul>           
                                    <li> <a href="javascript:void(0);" onclick="mostrarActividades();"> Mostrar Actividades </a> </li>
                                    <li> <a href="javascript:void(0);"> Otros </a> </li>
                                </ul>
                             </div>
                    </div>
                </div> 
        <div class="Clear"></div>
        <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr()">
            </div>
        <?php $view['slots']->stop(); ?>
        <?php $view['slots']->start("center"); ?>
        <div class="perfil">
                <div id="codQR"></div>
                <div id="actividades" class="hiddenStructure">
                    <?php if($actividades !== NULL and count($actividades) >0): ?>
                        <table border="1" style='text-align: center;'>
                            <th>Asignatura</th>
                            <th>Actividad</th>
                            <th>Descripción</th>
                            <th>Peso(%)</th>
                            <th>Puntuación</th>
                            <?php foreach ($actividades as $act) :?>
                                <tr>
                                    <td><?php echo $act->getIdAsignatura()->getNombre(); ?></td>
                                    <td><?php echo $act->getNombre(); ?></td>
                                    <td><?php echo $act->getDescripcion(); ?></td>
                                    <td><?php echo $act->getPeso()*100; ?></td>  
                                    <td><?php echo $act->getPeso()*$act->getNota(); ?></td>    
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
                <?php if (count($asignaturas)>0) :?>
                    <?php foreach ($asignaturas as $as): ?>
                    <div id="formRegistrar_<?php echo $as->getId(); ?>" class="hiddenStructure">
                        <form enctype="multipart/form-data" id="registrar_token_<?php echo $as->getId(); ?>" action="<?php echo $view['router']->generate('si_sigue_alumno_token',array("id"=>$alumno->getIdalumno(),"asig"=>$as->getId())); ?>" method="POST">
                            <label for="token" class="labelToken">TOKEN: </label>
                            <input type="text" name="codigo" placeholder='Código' class='validate[required]'/>
                            <input type="submit" value="Registrar" id="bRegistrar_<?php echo $as->getId(); ?>" />
                        </form>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div id="divCambiar" class="hiddenStructure">
                    <form method="post" id="mainCambiar" action="<?php echo $view['router']->generate('si_sigue_perfil_editar', array('id' => $alumno->getIdalumno())); ?>">
                        <label for="nueva_clave" class="labelContraseña">Nueva contraseña:</label>
                        <input type="password" id ="nueva_clave" name="nueva_clave" placeholder="Contraseña" class='validate[required,minSize[5]]'/>
                        <label for="verificar" class="labelContraseña">Repita contraseña:</label>
                        <input type="password" id="verificar" name="verificar" placeholder="Contraseña" class="validate[required,funcCall[fnVerificar],minSize[5]]"/>
                        <input type="submit" value="Cambiar" id="bCambiar" />
                    </form>
                </div>
                <div id="divCorreoAdicional" class="hiddenStructure">
                    <form method="post" id="mainCorreoAdicional" action="<?php echo $view['router']->generate('si_sigue_perfil_correoAdicional', array('id' => $alumno->getIdalumno())); ?>">
                        <label for="correo_adicional" class="labelCorreo">Correo Adicional:</label>
                        <input type="text" name="correo_adicional" placeholder="Correo Adicional" class='validate[required,custom[email]]'/>
                        <input type="submit" value="Añadir" id="bCorreoAdicional" />
                    </form>
                </div>
                <?php if(isset($res)): ?>
                    <?php if($res == 1): ?>
                        <div id="tooltip_exito">
                            <p>Se ha modificado correctamente tu contraseña.</p>
                        </div>
                    <?php elseif ($res == 2): ?>
                        <div id="tooltip_exito">
                            <p>Se añadió correctamente tu correo adicional.</p>
                        </div>
                    <?php elseif ($res == 3): ?>
                        <div id="tooltip_exito">
                            <p>EL TOKEN ES INVÁLIDO</p>
                            <p>Debes de ingresas un código registrado por el profesor y que no sea repetido. </p>
                        </div>
                    <?php elseif ($res == 4): ?>
                        <div id="tooltip_exito">
                            <p>TOKEN REGISTRADO CORRECTAMENTE.</p>
                        </div>
                    <?php else: ?>
                        <div id="tooltip_exito">
                            <p>La operación no se ha podido realizar.</p>
                        </div>
                    <?php endif;?>
                <?php endif;?>
                <div id="estadisticasAlumnoAsignatura">
                <?php if (isset($est) and $est !== NULL): ?>
                    <?php if ($est['total'] > 0 and $est['num']>0): ?>
                    <p><a href="javascript:void(0);" onclick="mostrarGeneral();">Estadísticas generales</a></p>
                    <div id="general" class="hiddenStructure">
                        <p>Número total de TOKENS de esta asignatura es: <?php echo $est['total'];?></p>
                        <p>Tu número de TOKENS de esta asignatura es: <?php echo $est['num'];?></p>
                        <p>Los máximos TOKENS obtenidos de esta asignatura es: <?php echo $est['max'];?></p>
                    </div>
                    <script type="text/javascript">
                        google.load('visualization', '1.0', {'packages':['corechart']});
                        google.setOnLoadCallback(drawChart);
                        function drawChart() {
                            // Create the data table.
                            var data = new google.visualization.DataTable();
                            data.addColumn('string', 'Topping');
                            data.addColumn('number', 'Slices');
                            var menos  = <?php echo $est['menos']; ?>;
                            var mas  = <?php echo $est['mas']; ?>;
                            data.addRows([
                              ['Alumnos con menos TOKENS', menos],
                              ['Alumnos con más TOKENS', mas]
                            ]);
                            var options = {'title':'Mis estadísticas',
                                           'width':400,
                                           'height':300,
                                           'backgroundColor': '#ceecf5',
                                           'legend.position': 'bottom',
                                           'legend.alignment':'center',
                                           'fontName': 'Comic Sans MS',
                                           'is3D':true};
                            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                            chart.draw(data, options);


                            }
                    </script>
                    <?php else:?>
                    <p><strong>No tienes ningún token....PONTE LAS PILAS!!!</strong></p>
                    <?php endif;?>
                <?php endif;?>

                <?php if(isset($estAlumnos) and $estAlumnos !== NULL): ?>
                    <script type="text/javascript">
                        google.load('visualization', '1.0', {'packages':['corechart']});
                        google.setOnLoadCallback(drawBarChart);
                        function drawBarChart() {
                            var nom = '<?php echo $alumno->getNombre();?>';
                            var yo = <?php echo $alumno->getIdalumno();?>;
                            var p =  <?php echo count($estAlumnos);?>;
                            var array = new Array(p+1);
                            array[0] = ['Alumno','Tokens'];
                            var i = 1;
                            <?php foreach ($estAlumnos as $e): ?>
                                var aux = <?php echo $e->getIdAlumno()->getIdalumno();?>;
                                var n = <?php echo $e->getNum();?>;
                                if(aux === yo){
                                    array[i] = [nom,n];
                                }else{
                                    array[i] = ['A',n];
                                }
                                i = i + 1;
                            <?php endforeach; ?>
                            var data = new google.visualization.arrayToDataTable(array);    
                            var options = {'title':'Alumnos-Tokens',
                                           'width':400,
                                           'height':300,
                                           'backgroundColor': '#ceecf5',
                                           'legend.position': 'bottom',
                                           'fontName': 'Comic Sans MS'};
                            var chart = new google.visualization.BarChart(document.getElementById('bar_3d'));
                            chart.draw(data, options);
                            }
                    </script>
                    <?php endif; ?>
                    <p><a href="javascript:void(0);" onclick="mostrarGraficaPorcentaje();">Gráfica Porcentaje de alumnos con mas/menos tokens</a></p>
                    <div id="piechart_3d" class="hiddenStructure"></div>
                    <p><a href="javascript:void(0);" onclick="mostrarGraficaAlumnosTokens();">Gráfica Alumnos-Tokens</a></p>
                    <div id="bar_3d" class="hiddenStructure"></div>
                    <p><a href="javascript:void(0);" onclick="mostrarPredicciones();">Predicción de la nota</a></p>
                    <div id="prediccion" class="hiddenStructure">
                    <?php if(isset($predicciones)): ?>
                        <p>Predicción de la nota de participación (a fecha de hoy) es:</p>
                        <ul>
                            <li><p><?php echo $predicciones ;?></p></li>
                        </ul>
                    <?php endif; ?>
                    </div>
                </div>
        <!--</div>-->
        <br>
        <br>
        
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    $(document).ready(function(){
        
         <?php if (isset($selected)): ?>
                 var s = <?php echo $selected; ?>;
                //$("#li_est").attr('href','javascript:void(0);');
                $("#li_est_" + s).attr('onClick',"mostrarEstadisticas(" + s +");");
                mostrarMenuAsignatura(s);
         <?php endif; ?>        
        
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
        if ($('#codQR').has('img').length){
            $("#actividades").addClass('hiddenStructure');
            $("div [id^='formRegistrar']").addClass("hiddenStructure");
            $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
            $('#divCambiar').addClass('hiddenStructure');
            $('#divCorreoAdicional').addClass('hiddenStructure');
            $("#codQR").removeClass('hiddenStructure');
        }else{         
            var cod = '<?php echo $cod; ?>';
            var url = '<?php echo "http://".$_SERVER['HTTP_HOST']. "/Symfony/"; ?>';
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
                        $("div [id^='formRegistrar']").addClass("hiddenStructure");
                        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
                        $("#actividades").addClass('hiddenStructure');
                        $('#divCambiar').addClass('hiddenStructure');
                        $('#divCorreoAdicional').addClass('hiddenStructure');
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
    
    function fnVerificar(field, rules, i, options){
        if ($("#nueva_clave").val() !== $("#verificar").val())
            return "*Las contraseñas no coinciden.";
    }
    
    function mostrarActividades(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $('#actividades').removeClass('hiddenStructure');
    }
    
    function mostrarFormPassword(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
        $('#divCambiar').removeClass('hiddenStructure');
    }
    
    function mostrarFormCorreo(){
        $("div [id^='formRegistrar']").addClass("hiddenStructure");
        $("ul [id^='opciones']").addClass("hiddenStructure"); 
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $("#estadisticasAlumnoAsignatura").addClass("hiddenStructure");
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
