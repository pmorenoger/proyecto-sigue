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
                    </td>
                    <td>
                        <p><strong>El número total de tokens que tienes es: <?php echo $total; ?></strong></p>
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
                            <?php else: ?>
                                <div id="tooltip_exito">
                                    <p>La operación no se ha podido realizar.</p>
                                </div>
                            <?php endif;?>
                        <?php endif;?>
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
                        $('#divCambiar').addClass('hiddenStructure');
                        $('#divCorreoAdicional').addClass('hiddenStructure');
                        $("#codQR").removeClass('hiddenStructure');
                        $("#codQR").append("<img src='../.." + data.dir + "'>");
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
        $('#codQR').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $('#actividades').removeClass('hiddenStructure');
    }
    
    function mostrarFormPassword(){
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCorreoAdicional').addClass('hiddenStructure');
        $('#divCambiar').removeClass('hiddenStructure');
    }
    
    function mostrarFormCorreo(){
        $('#codQR').addClass('hiddenStructure');
        $('#actividades').addClass('hiddenStructure');
        $('#divCambiar').addClass('hiddenStructure');
        $('#divCorreoAdicional').removeClass('hiddenStructure');
    }
</script>
<?php $view['slots']->stop(); ?>
