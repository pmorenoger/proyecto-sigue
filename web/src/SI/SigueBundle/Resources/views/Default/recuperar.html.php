<?php $view->extend('::layout3.html.php') ?>

<?php $view['slots']->set('rol', 'Recuperar Contraseña'); ?>


<?php $view['slots']->start("center"); ?>

<div class="perfil">
    <div class="mensaje">
        <h2> Recuperar Contraseña</h2>
    </div>
<div id="add_profesor" >
   
    <p>Introduzca su correo ucm dado de alta en la aplicación.</p>
    <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_recuperar_guardar');?>" method="POST">       
        <label for="correo" class="labelSigue">Correo</label>
        <input type="text" id="correo" name="correo" value="" placeholder="Correo" class="Centrar form-normal form-control"/>
        
        <input type="submit" value="Guardar" class=" left3 btn-20 btn-normal sigin btn btn-block"/>
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