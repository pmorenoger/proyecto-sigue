<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->
<div class="perfil">
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
    <div id="codQR">
        <img id="qrimagen" class="imagenSigue" src="<?php echo $dir; ?>" title="Qr con la info de tu login" />
    </div>
</div>
   
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    
</script>
<?php $view['slots']->stop(); ?>