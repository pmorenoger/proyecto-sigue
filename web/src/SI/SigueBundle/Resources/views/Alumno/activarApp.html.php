<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Alumno'); ?>

<?php echo $view->render(
    'SISigueBundle:Alumno:menu.html.php',
    array('asignaturas' => $asignaturas, 'alumno' => $alumno )
); ?>

<?php $view['slots']->start("center"); ?>
    <div class="perfil">
        <div id="codQR">
            <?php if (isset($dir)): ?>
                <h3>¡Activa tu aplicación Android!</h3>
                <p>Lo único que tienes que hacer es descargarla y escanear este código.</p>
                <p>¡Es la manera más simple de loguearse!</p>
                <img src="<?php echo $dir; ?>" title="Qr con la info de tu login" />
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
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    
</script>
<?php $view['slots']->stop(); ?>