<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>
<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, 'asignatura' => $asignatura )
        ); ?>
<?php $view['slots']->start("center"); ?>
    <div class="mensaje">
        <h2>Cambiar Password</h2>   
        <p>Si aún sigues con la contraseña predeterminada, te recomendamos que la cambies por una nueva.</p>
    </div>
    <div class="formSigue">
    <form id="cambiar_pass" method="POST" action="<?php echo $view['router']->generate('si_sigue_cambiar_password_guardar');?>" >
        <div class="form-group">
            <label class="labelSigue">Nueva Contraseña</label>
            <input type="password" id="password1" name="password" placeholder="Nueva Contraseña" class="Centrar form-normal form-control"/>
            <label class="labelSigue">Repetir Contraseña</label>
            <input type="password" id="password2" name="password2" placeholder="Repetir Contraseña" class="Centrar form-normal form-control"/>             
        </div>
        <input type="submit" name="submit" id="submit" value="Guardar" onclick="CheckPassword();return false;" class="btn-normal sigin btn btn-block"/>
    </form>
    </div>
<?php if($exito === "true"): ?>
    <div id="tooltip_exito"> 
        <p>¡Contraseña cambiada con éxito!</p>
        <p></p>
    </div>
<?php endif; ?>

<?php if($exito === "false"): ?>
    <div id="tooltip_exito"> 
        <p>¡Lo sentimos!</p>
        <p>No se ha podido llevar a cabo la operación.</p>
        
    </div>
<?php endif; ?>
<?php $view['slots']->stop(); ?>

      
<?php $view['slots']->start("javascripts"); ?>
      <script type="text/javascript">
          
           $(document).ready(function(){
           $("#accordion").accordion( "option", "active", 1 );

            });
          
          function CheckPassword(){   
            var p1 = $("#password1").val();
            //console.log(p1);
            var p2 = $("#password2").val();
        //console.log(p2);    
        if(p1 == p2)   
             {                 
                var x = document.getElementById("cambiar_pass");
                console.log(x);
                x.submit();
             }  
         else  
        {   
            alert('Las contraseñas introducidas no son iguales.');  
            return false;  
        }  
        }  

      
      
      
      
      
      
      </script>
      
<?php $view['slots']->stop(); ?>

      
    