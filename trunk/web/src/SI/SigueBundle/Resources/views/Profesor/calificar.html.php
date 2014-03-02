<?php $view->extend('::layout2.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>





<?php $view['slots']->start("center"); ?>
       <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>
<!-- Formulario para crear una nueva actividad. -->
<div>                        
    <div id="actividad_asignatura" class="hiddenStructure">
        <form id="factividad_form" method="POST" action="<?php echo $view['router']->generate('si_sigue_generar_actividad_profesor' );?>">
            <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
            <span>Crear una nueva Actividad en <h3><?php echo $asignatura->getNombre();?> </h3></span>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="" />
            <label for="peso">Peso en nota final (en %):</label>
            <input type="text" style="width:40px;" id="peso" name="peso" value="" />      
            <label for="descripcion">Descripción:</label>
            <textarea cols="30" rows="4" id="descripcion" name="descripcion" value=""></textarea>

            <input type="submit" value="Crear" />
            <input type="button" value="Cancelar" onclick="cancelar();return false;"
        </form>
    </div> 
    <div id="info" class="hiddenStructure">
        <h3>Importar/Exportar</h3>
        <p>La función de exportar/importar sirve para hacer más fácil la gestión de las calificaciones a partir
            de ficheros Excel. Para importar los datos, lo mejor es descargar primero la plantilla vacía (exportar)
            para tener todas las columnas necesarias.Toda la información del fichero subido sobrescribirá la información
            de la aplicación.            
        </p>
        
    </div>
    <div id="importar" class="hiddenStructure">
        <h3>Importar/Exportar</h3>
        <form enctype="multipart/form-data" id="importar" name="importar" action="<?php echo $view['router']->generate('si_sigue_exportar_calificaciones', array("id_asignatura" => $asignatura->getId()));?>" method="POST">
             <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
            <fieldset>
                <legend>Importar</legend>
                <label for="fichero">Fichero a importar</label>
                <input id="fichero" name="userfile" type="file" />
            </fieldset>
        </form>
         <form  enctype="multipart/form-data" id="exportar" name="exportar" action="<?php echo $view['router']->generate('si_sigue_exportar_calificaciones', array("id_asignatura" => $asignatura->getId()));?>" method="POST">
             <fieldset>
                 <legend>Exportar</legend>
                 <p>Se recomienda exportar primero si no se tiene la plantilla incial.</p>
                 <input type="button" id="exportar" name="exportar" value="Exportar"  />                 
             </fieldset>                                    
        </form>   
                
    </div>
    
    <div id="dinamic_form" class="hiddenStructure">
        <label for="nota">Nota</label>
        <span id="nota_dinamica">
            
        </span>
        <label for="observaciones">Observaciones</label>
            <span id="obs_dinamica">
            
            </span>
    </div>
    
    <div id="formulario_oculto">
        <form id="form_oculto" name="form_oculto" method="POST" action="<?php echo $view['router']->generate('si_sigue_guardar_calificaciones', array("id_asignatura"=>$asignatura->getId()) );?>">            
            <input type="button" id="actualizar" name="actualizar" class="hiddenStructure" value="Actualizar"/>
            <div id="campos_ocultos" class="hiddenStructure">
                
            </div>
        </form>
        
    </div>
    
    <div id="actividad_" >
        <table border="1" style='text-align: center;'>
            
            <th>Nombre</th>
            <th>Apellidos</th>
            <?php foreach($actividades as $actividad):?>            
            <th><a href="#" title="<?php echo $actividad["descripcion"]; ?>"><?php echo $actividad["nombre"]; ?> (<?php echo $actividad["peso"] ; ?>)</a></th>            
            <?php endforeach; ?>
            <th>TOKENS</th>
            <th>Nota TOKENS</th>
            <th>Nota Total</th>
            <th>Editar</th>
            <?php $ac_nota = 0; 
                $x  = 0;
            ?>
            <?php foreach($resultados as $fila):?>
                <tr>
                    <?php $y = 0; ?>
                    <td><?php echo $fila["alumno"]->getNombre(); ?></td>
                    <td><?php echo $fila["alumno"]->getApellidos() ; ?></td>   
                    <?php $array_actividades = $fila["actividades"];
                        foreach($array_actividades as $fila_actividad ):
                    ?>
                    <?php $color = "";
                        if($fila_actividad->getNota() === 0){
                            $color = "nota_vacia";
                        }
                        elseif($fila_actividad->getNota()<5){
                            $color="nota_rojo";
                        }elseif($fila_actividad->getNota()<8){
                            $color = "nota_naranja";
                        }else{
                            $color = "nota_verde";
                        }
                    ?>
                        <?php 
                        //Variables de control del input
                        $valor= $fila_actividad->getNota();
                        $id_asignatura2 = $asignatura->getId();
                        $nombre_actividad = str_replace(" ", "%/%",$fila_actividad->getNombre());
                        $observaciones = $fila_actividad->getObservaciones();
                        $id_alumno = $fila_actividad->getIdAlumno()->getIdAlumno();
                        
                 
                       
                        ?>
                    <td><a href="#"  onclick="add(<?php echo $valor.",'".$observaciones."',".$id_asignatura2.",".$id_alumno.",'".$nombre_actividad."'".",".$x.",".$y ;?>)" class="<?php echo $color;?>" title="<?php echo $observaciones; ?>"><?php echo $valor; ?><span id="<?php echo $x."_".$y ?>" class="hiddenStructure">&nbsp;*</span></a></td>
                    
                     <?php $ac_nota = $ac_nota +( $valor *  $fila_actividad->getPeso());?>
                    <?php $y++;?>
                    <?php endforeach;
                        $codigos = $fila["codigos"];
                         $codigos = $codigos[0];
                    ?>
                     <td><?php echo $codigos->getNum() ; ?></td>
                     <td>NotaTokens</td>
                     <td><?php echo $ac_nota; $ac_nota = 0;?></td>
                     <td><a href="<?php echo $view['router']->generate('si_sigue_calificar_actividad_profesor', array("id_asignatura" =>$asignatura->getId(), "id_alumno" => $fila["alumno"]->getIdAlumno()));?>" title="Editar los resultados del alumno <?php echo $fila["alumno"]->getNombre() . " " . $fila["alumno"]->getApellidos() ; ?>">Editar</a></td>
                </tr>           
                <?php $x++;?>
            <?php endforeach; ?>
          
                                                
        </table>
       
           
                                       
    </div>
   
</div>
 <div id="Nueva_actividad">
        <form >
            <input type="submit" value="Nueva Actividad" onclick="nueva_actividad(); return false;" />
            
            <input type="button" id="boton_importar" name="importar" value="Importar/Exportar" title="Importe o exporte listas de calificaciones de la asignatura mediante ficheros Excel."/>
            <a href="#" id="mas_info" title="Más información" >+info</a>
        </form>
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
           
 });


    $( "#info" ).dialog({
         autoOpen: false,
         buttons: {
            Ok: function() {
          $( this ).dialog( "close" );
        }
    }
    });
    $( "#importar" ).dialog({
         autoOpen: false,
         height: 500,
         width : 600,
         buttons: {
            Cerrar: function() {
          $( this ).dialog( "close" );
        }
    }
    });
    $("#mas_info").click(function() {
        $( "#info" ).dialog( "open" );
  });
  $("#boton_importar").click(function() {
        $( "#importar" ).dialog( "open" );
  });

function ocultar_todo(){
    $("#actividad_asignatura").addClass("hiddenStructure");
    $("div [id^='actividad_']").addClass("hiddenStructure");
}
           
function nueva_actividad(){
    ocultar_todo();
   
    $("#actividad_asignatura").removeClass("hiddenStructure");
}

function mostrar_actividad(id_actividad){
    ocultar_todo();
    $("#actividad_"+id_actividad).removeClass("hiddenStructure");
    
}
function cancelar(){
     ocultar_todo();
     $("#actividad_").removeClass("hiddenStructure");
}
           
           
           
//Añade dinámicamente campos editables para las notas//
function add(valor,valor2,id_asignatura,id_alumno,actividad, x,y) {
 
    //Create an input type dynamically.
    var nota = document.createElement("input");
 
    //Assign different attributes to the element.
    nota.setAttribute("type", "text");
    nota.setAttribute("value", valor);
    nota.setAttribute("name", "nota_"+id_asignatura+"_"+id_alumno+"_"+actividad);
    var actividad_remplazado = jq2(actividad);
    nota.setAttribute("id", "nota_"+id_asignatura+"_"+id_alumno+"_"+actividad_remplazado);
 
    var foo = document.getElementById("nota_dinamica");
 
    //Append the element in page (in span).
    foo.appendChild(nota);
    
     //Create an input type dynamically.
    var obs = document.createElement("textarea");
 
    //Assign different attributes to the element.
    obs.setAttribute("cols", "30");
    obs.setAttribute("rows", "40");  
    obs.setAttribute("name", "obs_"+id_asignatura+"_"+id_alumno+"_"+actividad);
    obs.setAttribute("id", "obs_"+id_asignatura+"_"+id_alumno+"_"+actividad_remplazado);
   
    obs.value=valor2;
 
    var foo2 = document.getElementById("obs_dinamica");
 
    //Append the element in page (in span).
    foo2.appendChild(obs);
    //Muestro el *;
    var id_td = "td_"+id_asignatura+"_"+id_alumno+"_"+actividad_remplazado;
    $("#"+x+"_"+y).removeClass("hiddenStructure");
    abrir_dialog();
    
    
        
}

function abrir_dialog(){
    $("#dinamic_form").dialog({   
        width:400,
        height:450,
         buttons: {
            Guardar: function() {
                copiar_y_borrar();
                mostrar_actualizar();
                $( this ).dialog( "close" );
        },
        Cancelar:function(){
             borrar();   
             $( this ).dialog( "close" );
        }
        
    },
     close: function(){           
            
     }
        
    });
}


function mostrar_actualizar(){
    $("#actualizar").removeClass("hiddenStructure");
    $("#actualizar").addClass("mostrarActualizar");
    

}

function borrar(){
    var nota = document.getElementById("nota_dinamica").children[0];
    var obs = document.getElementById("obs_dinamica").children[0];
    nota.parentNode.removeChild(nota); 
    obs.parentNode.removeChild(obs);
}
function borrar_ocultos(id_nota, id_obs){
    var nota = document.getElementById(id_nota);
    var obs = document.getElementById(id_obs);
    if(nota && obs){
        nota.parentNode.removeChild(nota);
        obs.parentNode.removeChild(obs);
    }
}

function copiar_y_borrar(){
//Cojo todo lo del formulario dinamico y lo copio en el formulario final.
//Tengo que tener en cuenta lo de los repetidos.
var nota = document.getElementById("nota_dinamica").children[0];
nota.id = jq2(nota.id);

var obs = document.getElementById("obs_dinamica").children[0];
obs.id = jq2(obs.id);

var id_nota = document.getElementById("f_"+nota.id);
var id_obs = document.getElementById("f_"+obs.id);
if(id_nota){
    borrar_ocultos(id_nota.id, id_obs.id );  
}

var form_oculto =  document.getElementById("campos_ocultos");
nota.id = "f_"+nota.id;
obs.id = "f_"+obs.id;
form_oculto.appendChild(nota);
form_oculto.appendChild(obs);
}

function jq( myid ) {
    return "#" + myid.replace( /(:|\%\/\%|\[|\])/g, "---" );
}
function jq2( myid ) {
    return myid.replace( /(:|\%\/\%|\[|\])/g, "---" );
}

$("#actualizar").click(function(){
    document.form_oculto.submit();    
});

$("#exportar").click(function(){
    document.exportar.submit();
});
</script>
<?php $view['slots']->stop(); ?>