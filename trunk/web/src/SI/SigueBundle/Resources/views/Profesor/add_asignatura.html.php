<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>

<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>


<?php $view['slots']->start("center"); ?>

<div class="perfil">
 <div id="codQR"></div>
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

<div id="nueva_asignatura" >
    <form enctype="multipart/form-data" action="subir_alumno" method="POST">
        <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
        Enviar este archivo: <input name="userfile" type="file" />
        <br />
        <br />
        <label for="nombre_asignatura" >Nombre de la asignatura: </label>
        <input id="nombre_asignatura" type="text" name="nombre_asignatura" />
        <label for="curso" > Curso: </label>
            <select id="curso" name="curso" >
                <option value="2012/2013" >2012/2013</option>
                <option value="2013/2014" >2013/2014</option>
                <option value="2014/2015" >2014/2015</option>                       
            </select>
        <label for="grupo" > Grupo: </label>
        <select id="grupo" name="grupo" >
            <option value="A" >A</option>
            <option value="B" >B</option>
            <option value="C" >C</option>
            <option value="D" >D</option>
            <option value="E" >E</option>
            <option value="F" >F</option>
            
        </select>
       
        <input type="submit" value="Enviar" />
    </form>
<h3>Envíe un archivo Excel con la información de los alumnos y complete el formulario para añadir una asignatura.</h3>

</div>



   
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
   $(document).ready(function(){
  $("#accordion").accordion( "option", "active", 1 );
         
       
   });
         
      
             
      
       
</script>
<?php $view['slots']->stop(); ?>