<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Alumno'); ?>

<?php $view['slots']->start("center"); ?>
    <div class="perfil">
        <div class="encabezado3">
            <h3>Bienvenido  <?php echo $alumno->getNombre(); ?></h3> 
        </div> 
        <!--<div class="accordionCentro">-->
        <table >
            <tr>
                <td>
                    <div id="accordion-resizer" class="ui-widget-content">
                        <div id="accordion">
                            <h3>Curso 2013/2014 .</h3>
                                 <div>
                                    <ul>           
                                        <li> <a href=""> PLg </a> </li>
                                        <li> <a href=""> EE </a> </li>
                                        <li> <a href=""> IS </a> </li>
                                        <li> <a href=""> IAIC </a> </li>
                                    </ul>
                                 </div>
                            <h3>Curso 2013/2014 .</h3>
                                <div>
                                    <ul>           
                                        <li> <a href=""> ISBC </a> </li>
                                        <li> <a href=""> IGr </a> </li>
                                        <li> <a href=""> SI </a> </li>
                                        <li> <a href=""> PDA </a> </li>
                                    </ul>
                                 </div>
                          </div>
                        </div>
                    </td>
                    <td>
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
