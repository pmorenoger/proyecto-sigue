<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Inicio'); ?>

<?php $view['slots']->start("menu_left"); ?>  
   <!-- AQUI VA EL MENU DE LA IZQUIERDA -->
    <div id="accordion-resizer" class="ui-widget-content">
        <div id="accordion">
            <!--AQUI IREMOS RECORRIENDO LAS ASIGNATUAS REALES DEL PROFESOR -->
            <h3>Curso 2012/2013</h3>
                 <div>
                    <ul>           
                        <li> <a href="#"> PLg </a> </li>
                        <li> <a href="#"> EE </a> </li>
                        <li> <a href="#"> IS </a> </li>
                        <li> <a href="#"> IAIC </a> </li>
                    </ul>
                 </div>
            <h3>Curso 2013/2014</h3>
                <div>
                    <ul>           
                        <li> <a href="#"> ISBC </a> </li>
                        <li> <a href="#"> IGr </a> </li>
                        <li> <a href="#"> SI </a> </li>
                        <li> <a href="#"> PDA </a> </li>
                    </ul>
                 </div>          
        </div> 
    </div>
   

<?php $view['slots']->stop(); ?>



<?php $view['slots']->start("center"); ?>
<!-- AQUI IRAN LAS OPCIONES QUE TENGA EL PROFESOR -->

 <?php if($exito==="true"): ?>
    <div id="tooltip_exito"> 
        <p>¡La lista se ha cargado con éxito!</p>
        <p>En breve tendrá acceso a todos los datos del curso introducido.</p>
    </div>
<?php endif; ?>

<?php if($exito ==="false"): ?>
     <div id="tooltip_exito"> 
        <p>¡Lo sentimos!</p>
        <p>No se ha podido procesar la solicitud. Inténtelo de nuevo más tarde.</p>
    </div>
<?php endif;?>
<div style="margin-left:750px;">
    <form enctype="multipart/form-data" action="subir_alumno" method="POST">
        <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
        Enviar este archivo: <input name="userfile" type="file" />
        <br />
        <input type="submit" value="Enviar Fichero" />
    </form>
<h3>Envíe un archivo Excel para que sea subido y procesado al servidor</h3>

</div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("menu_right"); ?> 
       
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
       
</script>
<?php $view['slots']->stop(); ?>