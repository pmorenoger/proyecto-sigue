<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>
<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>


<?php $view['slots']->start("center"); ?>
<div class="perfil">
<div class="mensaje">    
    <h3>Estadísticas de <?php echo $asignatura->getNombre(). " - Grupo ".$asignatura->getGrupo() . " (". $asignatura->getCurso().") "; ?></h3>
</div>
<div id="generados-redimidos" class="Marco"></div>
<div id="total_por_alumno" class="Marco"></div>
<div id="grafico_fechas" class="Marco"></div>

 <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        //Gráfico de tarta para comparación generados/redimidos //
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Generados', 'Redimidos'],
          ['Generados',     <?php echo $totales; ?>],
          ['Redimidos',     <?php echo $redimidos; ?>]        
        ]);

        var options = {
          title: 'Generados/Redimidos',
          is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('generados-redimidos'));
        chart.draw(data, options);
      }
    </script>
    
     <script type="text/javascript">
         //gráfico de barras para el número de códigos canjeados por los alumnos //
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([   
        ['Alumno', 'Codigos'],    
        <?php foreach($alumnos as $alumno) :?>
              <?php echo "['".$alumno->getIdAlumno()->getNombre()." ".$alumno->getIdAlumno()->getApellidos()."', ".$alumno->getNum()." ],"; ?>
        <?php endforeach; ?>
        ]);

        var options = {
          title: 'Gráfico TOTAL Alumnos',
          vAxis: {title: 'Alumno',  titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.BarChart(document.getElementById('total_por_alumno'));
        chart.draw(data, options);
      }
    </script>
     <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Fecha', 'Redimidos'],
          <?php foreach( $fechas_veces as $fechas) : ?>
                <?php echo "['". $fechas["fecha"]."' , " . $fechas["veces"]."],"; ?>        
          <?php endforeach; ?>
        ]);
        var options = {
          title: 'Gráfico de Fechas en las que se canjearon los códigos'
        };

        var chart = new google.visualization.LineChart(document.getElementById('grafico_fechas'));
        chart.draw(data, options);
      }
      
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
       });
      
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
    </script>
</div>
<?php $view['slots']->stop(); ?>
