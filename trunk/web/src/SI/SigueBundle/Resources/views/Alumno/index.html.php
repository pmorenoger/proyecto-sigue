<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Inicio'); ?>

<?php $view['slots']->start("menu_left"); ?>  
    AQUI VA EL MENU DE LA IZQUIERDA ALUMNO

<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("menu_right"); ?> 
        <a href="http://www.ucm.es" title="UCM" >
            <img src="<?php echo $view['assets']->getUrl('img/ucm_logo.gif') ?>" alt="Logo de la UCM" title="UCM" width="300px" />
        </a>   
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("center"); ?>
AQUI IRAN LAS OPCIONES QUE TENGA EL ALUMNO
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>

<?php $view['slots']->stop(); ?>