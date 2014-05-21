<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>
<?php $view['slots']->start("center"); ?>
<div class="perfil">
<div class="encabezado3">    
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
    </script>
</div>
<?php $view['slots']->stop(); ?>
