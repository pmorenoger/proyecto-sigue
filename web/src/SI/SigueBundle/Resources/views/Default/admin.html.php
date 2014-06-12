<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Admin'); ?>

<?php $view['slots']->start("menu_left"); ?>  
   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
    <div id="accordion-resizer" class="ui-widget-content">
         <div id="accordion">   
             <h3>Opciones</h3>
                <div id="opciones">
                    <ul class="lista_opciones list2">
                       <li>
                           <a href="#" onclick="add_profesor();">Añadir Profesor </a> 
                       </li>                                      
                    </ul>
                </div>                      
          </div>
    </div>    
<?php $view['slots']->stop(); ?>






<?php $view['slots']->start("center"); ?>
 <div class="perfil">   
<h3> Menú administración </h3>
<div id="add_profesor" class="hiddenStructure add_profesor">
    <div class="mensaje"><h2>Añadir Profesor </h2></div>
    <p>Rellene el formulario para agregar un profesor al sistema.</p>
    <form enctype="multipart/form-data" action="<?php echo $view['router']->generate('si_sigue_add_profesor');?>" method="POST">

        <label for="nombre" class="labelSigue">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="" placeholder="Nombre" class="Centrar form-normal form-control"/>
        <label for="apellidos" class="labelSigue">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" value="" placeholder="Apellidos" class="Centrar form-normal form-control"/>
        <label for="correo" class="labelSigue">Correo</label>
        <input type="text" id="correo" name="correo" value="" placeholder="Correo" class="Centrar form-normal form-control"/>        
        <input type="submit" value="Guardar" class=" left3 btn-20 btn-normal sigin btn btn-block"/>
    </form>
</div>
    
    
</div>
<?php if($exito=== "true"): ?>
    <div id="tooltip_exito"> 
        <p>¡Profesor añadido con éxito!</p>
        <p>En breve recibirá un correo con su contraseña para utilizar la aplicación.</p>
    </div>
<?php endif; ?>

<?php if($exito=== "false"): ?>
    <div id="tooltip_exito"> 
        <p>El profesor no ha podido ser añadido.</p>
        <p>Es posible que ya exista un profesor dado de alta con dicho correo.</p>
    </div>
<?php endif; ?>


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