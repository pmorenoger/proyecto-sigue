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
                                        <li> <a href=""> Perfil </a> </li>
                                        <li> <a href=""> Otros </a> </li>
                                 </div>
                          </div>
                        </div>
                    </td>
                    <td>
                        <p><strong>El número total de tokens que tienes es: <?php echo $total ?></strong></p>
                        <div id="codQR">

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
                    $("#codQR").append("<img src='../.." + data.dir + "'>");
                    $("#bActivar").attr("disabled", "disabled");
                }else{
                    $("#bActivar").attr("disabled", "disabled");
                }
             }
        });
    }
</script>
<?php $view['slots']->stop(); ?>
