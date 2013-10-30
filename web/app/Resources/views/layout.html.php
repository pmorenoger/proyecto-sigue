<?php $view->extend('::base.html.php') ?>

<div id="top">    
   <div class="titles">        
            <img src="../img/logo.png" ALIGN="middle">
            <text class="encabezado">IGUE</text> 
            <text class="encabezado2"> <?php $view['slots']->output('rol') ?></text>       
    </div>
</div>


<div id="left">
     <?php $view['slots']->output('menu_left') ?>
</div>
<div id="center">
     <?php $view['slots']->output('center') ?>
</div>
<div id="right">
     <?php $view['slots']->output('menu_right') ?>
</div>

<?php
?>
