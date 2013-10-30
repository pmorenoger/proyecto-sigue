<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php $view['slots']->start("menu_left"); ?>
    <div >
        <div class="encabezado3">
            <h3>BIENVENIDO A TU PERFIL</h3>
        </div>
        <!--<div class="accordionCentro">-->
        <table>
            <tr>
                <td>                    
                    <div id="accordion" class="profesor">
                         <!-- AQUI DEBEN IR LAS ASIGNATURAS CONTROLADAS POR EL PROFESOR-->
                        <h3>Curso 2012/2013</h3>
                             <div>
                                <ul>  
                                    <li> <a href="#"> PLg </a> </li>
                                    <li> <a href="#"> EE </a> </li>
                                    <li> <a href="#"> IS </a> </li>
                                    <li> <a href="#"> IAIC </a> </li>
                                </ul>
                             </div>
                        <h3>Curso 2013/2014</h3>
                            <div>
                                <ul>           
                                    <li> <a href="#"> ISBC </a> </li>
                                    <li> <a href="#"> IGr </a> </li>
                                    <li> <a href="#"> SI </a> </li>
                                    <li> <a href="#"> PDA </a> </li>
                                </ul>
                             </div>
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


<?php $view["slots"]->start("center"); ?>
    <div>
        <h4>OPCIONES DEL PROFESOR</h4>
        <ul>           
            <li> <label for="add_group">OPCION 1: </label> <input id="add_group" type="button" value="AÑADIR GRUPO" /> </li>
            <li> <label for="add_group">OPCION 2: </label> <input id="add_group" type="button" value="AÑADIR GRUPO" /></li>
            <li> <label for="add_group">OPCION 3: </label> <input id="add_group" type="button" value="AÑADIR GRUPO" /></li>
            <li> <label for="add_group">OPCION 4: </label> <input id="add_group" type="button" value="AÑADIR GRUPO" /> </li>
        </ul>
     </div>



<?php $view["slots"]->stop(); ?>



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
        $.ajax({
            type:"GET",
            url: "../../vendor/generadorQR.php",
            async: true,
            dataType:"text",
            success: function(data) {
                if (data==='ok'){
                    alert("CODIGO GENERADO");
                    $("#codQR").append("<img src='../img/ejemplo.png'>");
                    $("#bActivar").attr("disabled", "disabled");
                }
             }
        });
    }
</script>
<?php $view['slots']->stop(); ?>
