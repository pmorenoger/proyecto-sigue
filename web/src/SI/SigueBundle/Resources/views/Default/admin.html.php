<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Admin'); ?>

<?php $view['slots']->start("menu_left"); ?>  
   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
    <div id="accordion-resizer" class="ui-widget-content">
         <div id="accordion">   
             <h3>Opciones</h3>
                <div id="opciones">
                    <ul>
                       <li>
                           <a href="#" onclick="add_profesor();">Añadir Profesor </a> 
                       </li>                                      
                    </ul>
                </div>                      
          </div>
    </div>    
<?php $view['slots']->stop(); ?>






<?php $view['slots']->start("center"); ?>
<h3> Menú administración </h3>
<div id="add_profesor" style="margin-left:550px;" class="hiddenStructure">
    <h3>Añadir Profesor </h3>
    <p>Rellene el formulario para agregar un profesor al sistema.</p>
    <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_add_profesor');?>" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" />
        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" />
        <label for="correo">Correo</label>
        <input type="text" id="correo" name="correo" />
        
        <input type="submit" value="Guardar">
    </form>
    
    
    
</div>
<?php if($exito=== true): ?>
    <div id="tooltip_exito"> 
        <p>¡Profesor añadido con éxito!</p>
        <p>En breve recibirá un correo con su contraseña para utilizar la aplicación.</p>
    </div>
<?php endif; ?>

<?php if($exito=== false): ?>
    <div id="tooltip_exito"> 
        <p>El profesor no ha podido ser añadido.</p>
        <p>Es posible que ya exista un profesor dado de alta con dicho correo.</p>
    </div>
<?php endif; ?>


<a href="logout" title="Salir">Salir</a>

<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
    $(document).ready(function(){
           $("#accordion").accordion({
               heightStyle: "fill"
           });
           $( "#accordion-resizer" ).resizable({
               minHeight: 140,
               minWidth: 200,
               resize: function() {
                   $( "#accordion" ).accordion( "refresh" );
               }
           });
           
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
       
       function ocultar_todo(){
            $("#add_profesor").addClass("hiddenStructure");
       }
       
       function add_profesor(){
            ocultar_todo();
            $("#add_profesor").removeClass("hiddenStructure");
       
       }
</script>
<?php $view['slots']->stop(); ?>  