<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Recuperar Contraseña'); ?>


<?php $view['slots']->start("center"); ?>
<div id="center" class="center">
<h3> Recuperar Contraseña</h3>
<div id="add_profesor" >
   
    <p>Introduzca su correo ucm dado de alta en la aplicación.</p>
    <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_recuperar_guardar');?>" method="POST">       
        <label for="correo">Correo</label>
        <input type="text" id="correo" name="correo" />
        
        <input type="submit" value="Guardar">
        <a href="inicio" title="volver" class="tabulado_derecha">Volver </a>
    </form>
    
    
   
</div>
</div>
<?php if($exito=== "true"): ?>
    <div id="tooltip_exito"> 
        <p>!Exito!</p>
        <p>Revise su correo, se le ha enviado su nueva contraseña</p>
    </div>
<?php endif; ?>

<?php if($exito=== "false"): ?>
    <div id="tooltip_exito"> 
        <p>Error</p>
        <p>Es posible que no exista un usuario dado de alta con dicho correo.</p>
    </div>
<?php endif; ?>




<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    $(document).ready(function(){
          
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
              
             
          
       });
       
      
</script>
<?php $view['slots']->stop(); ?>  