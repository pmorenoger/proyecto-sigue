<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas )
        ); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->
<div class="perfil">
    <div class="mensaje">
        <h2>¡BIENVENIDO!</h2>

        <h3>El menú:</h3>
        <p>El menú de la izquierda tiene todas las opciones disponibles en la aplicación</p>
        <h3>Activar aplicación</h3>
        <p>Si aún no has activado la app Android, descárgala y logueate con el código QR que aparecerá después de clicar en el botón.</p>
    </div>
</div>  
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
 <script type="text/javascript">
                                                   
       
</script>
<?php $view['slots']->stop(); ?>