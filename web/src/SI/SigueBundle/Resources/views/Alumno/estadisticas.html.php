<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Alumno'); ?>

<?php echo $view->render(
    'SISigueBundle:Alumno:menu.html.php',
    array('asignaturas' => $asignaturas, 'alumno' => $alumno )
); ?>

<?php $view['slots']->start("center"); ?>
    <div class="perfil">
        <div id="estadisticasAlumnoAsignatura">
            <?php if (isset($est) and $est !== NULL): ?>
                <?php if ($est['total'] > 0 and $est['num']>0): ?>
                <p><a href="javascript:void(0);" onclick="mostrarGeneral();">Estadísticas generales</a></p>
                <div id="general" class="hiddenStructure Marco">
                    <p>Número total de TOKENS de esta asignatura es: <strong><?php echo $est['total'];?></strong></p>
                    <p>Tu número de TOKENS de esta asignatura es: <strong><?php echo $est['num'];?></strong></p>
                    <p>Los máximos TOKENS obtenidos de esta asignatura es: <strong><?php echo $est['max'];?></strong></p>
                </div>
                <p><a href="javascript:void(0);" onclick="mostrarGraficaPorcentaje();">Gráfica Porcentaje de alumnos con mas/menos tokens</a></p>
                <div id="piechart_3d" class="hiddenStructure Marco"></div>
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
                                       'width':572,
                                       'height':350,
                                       'backgroundColor': '#F0E68C',
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
            <p><a href="javascript:void(0);" onclick="mostrarGraficaAlumnosTokens();">Gráfica Alumnos-Tokens</a></p>
            <div id="bar_3d" class="hiddenStructure Marco"></div>
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
                                   'width':572,
                                   'height':350,
                                   'backgroundColor': '#F0E68C',
                                   'legend.position': 'bottom',
                                   'fontName': 'Comic Sans MS'};
                    var chart = new google.visualization.BarChart(document.getElementById('bar_3d'));
                    chart.draw(data, options);
                    }
            </script>
            <?php endif; ?> 
            <?php if(isset($predicciones)): ?>
            <p><a href="javascript:void(0);" onclick="mostrarPredicciones();">Predicción de la nota</a></p>
            <div id="prediccion" class="hiddenStructure Marco">                   
                <p>Predicción de la nota de participación (a fecha de hoy) es:  <strong><?php echo $predicciones ;?></strong></p>
            </div>
            <?php endif; ?>
        </div>
        <div id="actividades" class="hiddenStructure">
            <?php if($actividades !== NULL and count($actividades) >0): ?>
                <table class="tablaActividades">
                    <th>Asignatura</th>
                    <th>Actividad</th>
                    <th>Descripción</th>
                    <th>Peso(%)</th>
                    <th>Puntuación</th>
                    <?php foreach ($actividades as $act) :?>
                        <tr>
                            <td><?php echo $act->getIdAsignatura()->getNombre(); ?></td>
                            <td><?php echo $act->getNombre(); ?></td>
                            <td style="min-width: 250px;"><?php echo $act->getDescripcion(); ?></td>
                            <td><?php echo $act->getPeso()*100; ?></td>  
                            <td><?php echo $act->getPeso()*$act->getNota(); ?></td>    
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <?php if (count($asignaturas)>0) :?>
            <?php foreach ($asignaturas as $as): ?>
            <div id="formRegistrar_<?php echo $as->getId(); ?>" class="hiddenStructure formSigue">
                <form enctype="multipart/form-data" id="registrar_token_<?php echo $as->getId(); ?>" action="<?php echo $view['router']->generate('si_sigue_alumno_token',array("id"=>$alumno->getIdalumno(),"asig"=>$as->getId())); ?>" method="POST">
                    <div class="form-group">
                        <input type="text" name="codigo" placeholder='Inserte es código QR' class="Centrar form-normal form-control validate[required]"/>
                    </div>
                    <input type="submit" value="Registrar" id="bRegistrar_<?php echo $as->getId(); ?>" class="btn-normal sigin btn btn-block" />
                </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div id="divCambiar" class="hiddenStructure formSigue">
            <form method="post" id="mainCambiar" action="<?php echo $view['router']->generate('si_sigue_perfil_editar', array('id' => $alumno->getIdalumno())); ?>">
                <input type="password" id ="nueva_clave" name="nueva_clave" placeholder="Nueva Contraseña" class='Centrar form-normal form-control validate[required,minSize[5]]'/>
                <input type="password" id="verificar" name="verificar" placeholder="Repetir Contraseña" class="Centrar form-normal form-control validate[required,funcCall[fnVerificar],minSize[5]]"/>
                <input type="submit" value="Cambiar" id="bCambiar" class="btn-normal sigin btn btn-block"/>
            </form>
        </div>
        <div id="divCorreoAdicional" class="hiddenStructure formSigue">
            <form method="post" id="mainCorreoAdicional" action="<?php echo $view['router']->generate('si_sigue_perfil_correoAdicional', array('id' => $alumno->getIdalumno())); ?>">
                <input type="text" name="correo_adicional" placeholder="Correo Adicional" class='Centrar form-normal form-control validate[required,custom[email]]'/>
                <input type="submit" value="Añadir" id="bCorreoAdicional" class="btn-normal sigin btn btn-block"/>
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
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    
</script>
<?php $view['slots']->stop(); ?>