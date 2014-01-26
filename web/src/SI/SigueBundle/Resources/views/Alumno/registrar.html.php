<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Alumno '.$alumno->getNombre()); ?>

<?php $view['slots']->start("center"); ?>
<div class="perfil">
    <div class="encabezado3">
        <h3>Asignatura  <?php echo $asignatura->getNombre(); ?></h3> 
    </div>
    <table>
    <tr>
    <td>
    <div id="accordion-resizer" class="ui-widget-content">
        <div id="accordion">
            <h3>Registar un nuevo TOKEN</h3>
                <div>
                    <form enctype="multipart/form-data" id="registrar_token" action="<?php echo $view['router']->generate('si_sigue_alumno_token',array("id"=>$alumno->getIdalumno(),"asig"=>$asignatura->getId())); ?>" method="POST">
                        <label for="token" class="labelToken">TOKEN: </label>
                        <input type="text" name="codigo" placeholder='Código' class='validate[required]'/>
                        <input type="submit" value="Registrar" id="bRegistrar" />
                    </form>
                    
                    <?php if($res == 2): ?>
                        <div id="tooltip_exito">
                            <p>TOKEN REGISTRADO CORRECTAMENTE</p>
                        </div>
                    <?php elseif ($res == 1): ?>
                        <div id="tooltip_exito">
                            <p>EL TOKEN ES INVÁLIDO</p>
                            <p>Debes de ingresas un código registrado por el profesor y que no sea repetido. </p>
                        </div>
                    <?php endif;?>
                    
                </div>
            <h3>Ver estadísticas</h3>
                <div>
                    <ul>
                        <li><a href="<?php echo $view['router']->generate('si_sigue_alumno_estadisticas', array('id' => $alumno->getIdalumno(),'asig'=>$asignatura->getId()),true); ?>"> Mis TOKENS </a> 
                            <ul>
                                <li><a href="javascript:void(0);" onclick="mostrarGraficaPorcentaje();"> Gráfica Porcentaje de alumnos con mas/menos tokens </a></li>
                                <li><a href="javascript:void(0);" onclick="mostrarGraficaAlumnosTokens();"> Gráfica Alumnos-Tokens </a></li>
                            </ul>    
                        </li>
                    </ul>
                </div>
        </div>
    </div>
    </td>
    <td>       
        <div id="estadisticasAlumnoAsignatura">
            <?php if ($est !== NULL): ?>
                <?php if ($est['total'] > 0 and $est['num']>0): ?>
                <p>Número total de TOKENS de esta asignatura es: <?php echo $est['total'];?></p>
                <p>Tu número de TOKENS de esta asignatura es: <?php echo $est['num'];?></p>
                <p>Los máximos TOKENS obtenidos de esta asignatura es: <?php echo $est['max'];?></p>
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
            
            <div id="piechart_3d"></div>
            <div id="bar_3d" class="hiddenStructure"></div>
        </div>
    </td>
    </tr>
    </table>
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
        
        $("#bRegistrar").submit(fnOnSubmit);
        $("#registrar_token").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        
        function fnOnSubmit(){
                if (!$("#bRegistrar").validationEngine('validate')) return false;
                return true;
        }
        
        
    });
    function mostrarGraficaPorcentaje(){
        $("#piechart_3d").removeClass("hiddenStructure");
        $("#bar_3d").addClass("hiddenStructure");
    }
        
    function mostrarGraficaAlumnosTokens(){
        $("#piechart_3d").addClass("hiddenStructure");
        $("#bar_3d").removeClass("hiddenStructure");
    }
    
</script>
<?php $view['slots']->stop(); ?>
