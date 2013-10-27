<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Prueba'); ?>
<?php $view['slots']->start("menu_left"); ?>
    <div id="left">
        <img src="<?php echo $view['assets']->getUrl('img/profe_der.jpg') ?>" alt="Profesora en la pizarra" title="Profesionales de la enseñanza"/>
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("menu_right"); ?>
 <div id="right">
        <a href="http://www.ucm.es" title="UCM" >
            <img src="<?php echo $view['assets']->getUrl('img/ucm_logo.gif') ?>" alt="Logo de la UCM" title="UCM" width="300px" />
        </a>
    </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("center"); ?>

<?php echo "HA FUNCIONADO!! EL ID es -> ". $view->escape($idalumno);?>

<?php $view['slots']->stop(); ?>