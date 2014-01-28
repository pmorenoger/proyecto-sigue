<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Alumno'); ?>

<?php $view['slots']->start("center"); ?>
    <div class="perfil">
        <div class="encabezado3">
            <h3>Bienvenido  <?php echo $alumno->getNombre(); ?></h3> 
        </div>
        <table >
            <tr>
                <td>
                    <div id="accordion-resizer" class="ui-widget-content">
                        <div id="accordion">
                            <?php if (count($asignaturas)>0) :?>
                            <h3>Curso 2013/2014 .</h3>
                                 <div>
                                    <ul>
                                        <?php foreach ($asignaturas as $as): ?>
                                        <li> <a href="<?php echo $view['router']->generate('si_sigue_alumno_registrar', array('id' => $alumno->getIdalumno(),'asig'=>$as->getId()),true); ?>"> <?php echo $as->getNombre();?> </a> </li>
                                        <?php endforeach; ?>
                                    </ul>
                                 </div>
                            <?php endif; ?>
                            <h3>Otras Opciones</h3>
                                <div>
                                    <ul>           
                                        <li> <a href="javascript:void(0);" onclick="mostrarActividades();"> Mostrar Actividades </a> </li>
                                        <li> <a href="javascript:void(0);"> Otros </a> </li>
                                    </ul>
                                 </div>
                          </div>
                        </div>
                    </td>
                    <td>
                        <p><strong>El número total de tokens que tienes es: <?php echo $total ?></strong></p>
                        <div id="codQR">

                        </div>
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
                    </td>
            </tr>
        </table>
        <!--</div>-->
        <br>
        <br>
        <input class="bActivar" type="button" value="Activar Aplicación" id="bActivar" onclick="qr()">
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
    });
    
    function qr(){
        if ($('#codQR').has('img').length){
            $("#actividades").addClass('hiddenStructure');
            $("#codQR").removeClass('hiddenStructure');
        }else{
            cod = '<?php echo $alumno->getCodigo_id(); ?>';
            $.ajax({
                type:"GET",
                url: "../../../vendor/generadorQR.php",
                async: true,
                data: {
                   codigo: cod 
                },
                dataType:"json",
                success: function(data) {
                    if (data.status){
                        $("#actividades").addClass('hiddenStructure');
                        $("#codQR").removeClass('hiddenStructure')
                        $("#codQR").append("<img src='../.." + data.dir + "'>");
                        //$("#bActivar").attr("disabled", "disabled");
                    }else{
                        $("#bActivar").attr("disabled", "disabled");
                    }
                 }
            });
        }
    }
    
    function mostrarActividades(){
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').removeClass('hiddenStructure');
    }
</script>
<?php $view['slots']->stop(); ?>
