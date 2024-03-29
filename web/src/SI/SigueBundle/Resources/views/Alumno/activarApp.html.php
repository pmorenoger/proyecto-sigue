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
            <div class="mensaje">
                <h3>¡Activa tu aplicación Android!</h3>
                <?php if($this->container->get('kernel')->getEnvironment() === "dev"){
                   $path = "/web/archivos/app/sigue.apk";            
               }else{
                   $path = "/archivos/app/sigue.apk";
               } ?>
               <p>Lo único que tienes que hacer es <a href="<?php echo $path;?>" title="descarga nuestra App Android">descargarla</a> y escanear este código.</p>
               <p>¡Es la manera más simple de loguearse!</p>
            </div>
            <div class="Centrar">
                <img src="<?php echo $dir; ?>" class="imagenSigue" title="Qr con la info de tu login" />
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
                <form enctype="multipart/form-data" id="registrar_token_<?php echo $as->getId(); ?>" action="<?php echo $view['router']->generate('si_sigue_alumno_token',array("asig"=>$as->getId())); ?>" method="POST">
                    <div class="form-group">
                        <label class="labelSigue" for="user">Token</label>
                        <input type="text" name="codigo" placeholder='Inserte es código QR' class="Centrar form-normal form-control validate[required]"/>
                    </div>
                    <input type="submit" value="Registrar" id="bRegistrar_<?php echo $as->getId(); ?>" class="btn-normal sigin btn btn-block" />
                </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div id="divCambiar" class="hiddenStructure formSigue">
            <form method="post" id="mainCambiar" action="<?php echo $view['router']->generate('si_sigue_perfil_editar'); ?>">
                <div class="form-group">
                    <label class="labelSigue" for="user">Nueva Contraseña</label>
                    <input type="password" id ="nueva_clave" name="nueva_clave" placeholder="Nueva Contraseña" class='Centrar form-normal form-control validate[required,minSize[5]]'/>
                    <label class="labelSigue" for="user">Repetir Contraseña</label>
                    <input type="password" id="verificar" name="verificar" placeholder="Repetir Contraseña" class="Centrar form-normal form-control validate[required,funcCall[fnVerificar],minSize[5]]"/>
                </div>
                <input type="submit" value="Cambiar" id="bCambiar" class="btn-normal sigin btn btn-block"/>
            </form>
        </div>
        <div id="divCorreoAdicional" class="hiddenStructure formSigue">
            <form method="post" id="mainCorreoAdicional" action="<?php echo $view['router']->generate('si_sigue_perfil_correoAdicional'); ?>">
                <div class="form-group">
                    <label class="labelSigue" for="user">Correo Adicional</label>
                    <input type="text" name="correo_adicional" placeholder="Correo Adicional" class='Centrar form-normal form-control validate[required,custom[email]]'/>
                </div>
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
                    <p>Debes ingresar un código generado por el profesor y que no sea repetido. </p>
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
    $(document).ready(function(){
       <?php if (isset($dir)): ?>
            $("#divInicio").addClass("hiddenStructure");
        <?php endif; ?> 
    });
</script>
<?php $view['slots']->stop(); ?>