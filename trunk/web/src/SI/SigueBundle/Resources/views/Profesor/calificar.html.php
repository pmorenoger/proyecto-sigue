<?php $view->extend('::layout.html.php') ?>

<?php $view['slots']->set('rol', 'Profesor'); ?>


<?php echo $view->render(
            'SISigueBundle:Profesor:menu.html.php',
            array('asignaturas' => $asignaturas, "asignatura" => $asignatura )
        ); ?>

<?php $view['slots']->start("center"); ?>
   <div class="perfil">
   <div id="centro_tabla" class="ancho_horizontal">
       <div class="mensaje">
            <h2>Actividades de <?php echo $asignatura->getNombre(); ?></h2>
       </div>
<!-- Formulario para crear una nueva actividad. -->
<div>                        
    <div id="actividad_asignatura" class="hiddenStructure formSigue" >
        <form id="factividad_form" method="POST" action="<?php echo $view['router']->generate('si_sigue_generar_actividad_profesor' );?>">
            <input type="hidden" name="id_asignatura" value="<?php echo $asignatura->getId();?>" />
            <input type="hidden" id="eliminar" name="eliminar" value ="" />
            <input type="hidden" name="nueva" id="nueva" value="si" />
            <input type="hidden" name="nombre_antiguo" id="nombre_antiguo" value="" />
            <div class="form-group">
                <h3>Detalles de Actividad en <strong><?php echo $asignatura->getNombre();?> </strong></h3>
                <label class="labelSigue">Nombre</label>
                <input type="text" id="nombre_nueva_actividad" name="nombre" value="" placeholder="Nombre" class="Centrar form-normal form-control"/>
                <label class="labelSigue">Peso en la nota final(en %)</label>
                <input type="text" style="width:40px;" id="peso_nueva_actividad" name="peso" value="" placeholder="Peso en nota final (en %)" class="Centrar form-normal form-control"/>      
                <label class="labelSigue">Descripción</label>
                <textarea cols="30" rows="4" id="descripcion_nueva_actividad" name="descripcion" value="" placeholder="Descripcion" class="Centrar form-normal form-control"></textarea>
            </div>
            <input type="submit" value="Crear" class=" left2 btn-20 btn-normal sigin btn btn-block"/>
            <input type="button" value="Cancelar" onclick="cancelar();return false;" class="left2 btn-20 btn-normal sigin btn btn-block"/>
            <input type="button" value="Eliminar" class="confirm rigth2 eliminar btn-20 btn-normal sigin btn btn-block"/>
        </form>
    </div> 
    <div id="info" class="hiddenStructure mensaje">
        <h3>Calificar</h3>
        <p>Para calificar, pulse sobre la nota para cambiarla individualmente. 
            También puede calificar todas las actividades de un alumno pulsando sobre el botón editar</p>
        <h3>Editar/Crear Actividades</h3>
        <p>Puede crear nuevas actividades pulsando sobre el botón "Nueva Actividad".
            También puede editar actividades pulsando sobre el nombre de cada una. Puede modificar su nombre
            y su peso sobre la nota final.
        </p>
        <h3>Importar/Exportar</h3>
        <p>La función de exportar/importar sirve para hacer más fácil la gestión de las calificaciones a partir
            de ficheros Excel. Para importar los datos, lo mejor es descargar primero la plantilla vacía (exportar)
            para tener todas las columnas necesarias.Toda la información del fichero subido sobrescribirá la información
            de la aplicación.            
        </p>
        
    </div>
    
    <?php if($exito=== true): ?>
    <div id="tooltip_exito" class="mensaje"> 
        <p>¡Notificación enviada con éxito!</p>       
    </div>
    <?php endif; ?>
    
    
    <div id="importar_div" class="hiddenStructure mensaje">
       
          <h3>Importar/Exportar</h3>
          <br />
          <form> </form>
          <form  enctype="multipart/form-data" id="importar_form" name="importar_form" action="<?php echo $view['router']->generate('si_sigue_importar_calificaciones'); ?>" method="post" >
              <fieldset> 
                    <legend>Importar</legend>
                    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />                      
                    <label for="fichero">Fichero a importar</label>
                    <input type="hidden" name="id_asignatura" id="id_asignatura_form" value="<?php echo $asignatura->getId();?>" />
                    <input id="fichero" name="userfile" type="file" />
                    <input type="button" id="importar" value="Enviar" />
            </fieldset>
         </form>       
          <br />
         <form  enctype="multipart/form-data" id="exportar_form" name="exportar_form" action="<?php echo $view['router']->generate('si_sigue_exportar_calificaciones', array("id_asignatura" => $asignatura->getId()));?>" method="post" >
            <fieldset>
                <legend>Exportar</legend>
                <p>Se recomienda exportar primero si no se tiene la plantilla incial.</p>
                <select id="selector_tipo" name="selector_tipo">
                    <option value="plantilla">Plantilla</option>
                    <option value="campus">Formato CV</option>
                    <option value="gea">Formato GEA</option>
                </select>
                <input type="button" id="exportar" name="exportar" value="Exportar"  />                 
            </fieldset>                                    
          </form>   
    </div>
    
    <form id="dinamic_form" class="hiddenStructure" method="POST" action="#">
        <label for="nota">Nota</label>
        <span id="nota_dinamica">
            
        </span>
        <label for="observaciones">Observaciones</label>
            <span id="obs_dinamica">
            
            </span>
    </form>
    
    <div id="formulario_oculto">
        <form id="form_oculto" name="form_oculto" method="POST" action="<?php echo $view['router']->generate('si_sigue_guardar_calificaciones', array("id_asignatura"=>$asignatura->getId()) );?>">            
            <input type="button" id="actualizar" name="actualizar" class="btn-20 btn-normal sigin btn btn-block hiddenStructure2" value="Actualizar"/>
            <div id="campos_ocultos" class="hiddenStructure">
                
            </div>
        </form>
        
    </div>
    
    <div id="calificador" class="tabla_horizontal">
        <a href="javascript:void(0);" id="showinfo" title="+info">+info</a>
        <table id="tabla_actividades" class="tablaActividades widthAuto">
            
            <th>Nombre</th>
            <th>Apellidos</th>
            <?php foreach($actividades as $actividad):?>            
            <th><a href="#" title="<?php echo $actividad["descripcion"]; ?>" style="color:white !important;" onclick="editar_actividad(<?php echo "'".$actividad["nombre"]."','".$actividad["peso"]."','".$actividad["descripcion"]."'" ; ?>);"><?php echo $actividad["nombre"]; ?> (<?php echo ($actividad["peso"]*100)."%"; ?>)</a></th>            
            <?php endforeach; ?>
            <th>TOKENS</th>
            <th><a href="<?php echo $view['router']->generate('si_sigue_metodo_evaluacion', array("id_asignatura"=>$asignatura->getId()) );?>" title="Cambiar valor de los tokens." style="color:white;" >Nota TOKENS</a></th>
            <th>Nota Total</th>
            <th>Editar</th>
            <?php $ac_nota = 0; 
                $x  = 0;
            ?>
            <?php foreach($resultados as $fila):?>
                <tr class="fila_calificaciones">
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
                        }elseif($fila_actividad->getNota()<7){
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
                     <td><?php $nota_tokens = $fila["nota_tokens"]; echo $nota_tokens ;?></td>
                     <td><?php $ac_nota =  $ac_nota+$nota_tokens;if($ac_nota > 10){
                         $ac_nota = 10;                         
                     }echo $ac_nota; $ac_nota = 0;?></td>
                     <td><a href="<?php echo $view['router']->generate('si_sigue_calificar_actividad_profesor', array("id_asignatura" =>$asignatura->getId(), "id_alumno" => $fila["alumno"]->getIdAlumno()));?>" title="Editar los resultados del alumno <?php echo $fila["alumno"]->getNombre() . " " . $fila["alumno"]->getApellidos() ; ?>">Editar</a></td>
                </tr>           
                <?php $x++;?>
            <?php endforeach; ?>
          
                                                
        </table>
                                
    </div>
   
</div>
   
</div>
   </div>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start("javascripts"); ?>
<script type="text/javascript">
 $(document).ready(function(){           
      mostrar_subopciones_asignatura(<?php echo $asignatura->getId();?>); 
      $("#dinamic_form").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
 });

    $("#showinfo").click(function(){
         
        $( "#info" ).dialog("open");
    });
   $( "#info" ).dialog({
            autoOpen: false,
            width: 500,
            buttons: {
               Ok: function() {
             $( this ).dialog( "close" );
           }
       }
    });
    $(".confirm").confirm({
    text: "¿Seguro que quiere elimnar la actividad? Si hay notas subidas se perderán todas.",
    title: "Confirmación requerida",
    confirm: function(button) {
       eliminar();
    },
    cancel: function(button) {
       
    },
    confirmButton: "Si, estoy seguro",
    cancelButton: "No",
    //post: true
});  
  
    
    
    
    $( "#importar_div" ).dialog({
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
  $("#boton_importar_<?php echo $asignatura->getId();?>").click(function() {
        $( "#importar_div" ).dialog( "open" );
        return false;
  });


function mostrar_tabla(){
    $("#calificador").removeClass("hiddenStructure");
}
function ocultar_tabla(){
     $("#calificador").addClass("hiddenStructure");
}

function ocultar_todo(){
    $("#actividad_asignatura").addClass("hiddenStructure");
    $("div [id^='actividad_']").addClass("hiddenStructure");
    $("div [id^='asignatura_']").addClass("hiddenStructure");
   $("div [id^='evaluacion_']").addClass("hiddenStructure");
   $("div [id^='evaluacion_']").addClass("hiddenStructure");
   $("div [id^='stats_codigos']").addClass("hiddenStructure");
   $("#codQR").addClass("hiddenStructure");
}


 function mostrar_opciones_asignatura(id_asignatura){    
               // ocultar_todo();
                $("ul [id^='lista_opciones']").addClass("hiddenStructure");
                $("#codQR").addClass("hiddenStructure");
                $("#lista_opciones_"+id_asignatura).removeClass("hiddenStructure");
                $("#nueva_asignatura").addClass("hiddenStructure");         
                //console.log("Ha llegado al de la id "+id_asignatura);
                return false;
         }
function mostrar_subopciones_asignatura(id_asignatura){
     $("ul [id^='lista_subopciones']").addClass("hiddenStructure");
     $("#lista_subopciones_"+id_asignatura).removeClass("hiddenStructure");
}        


function nueva_actividad(){
    ocultar_todo();
    ocultar_tabla();   
    $("#actividad_asignatura").removeClass("hiddenStructure");
}

function mostrar_actividad(id_actividad){
    ocultar_todo();
    $("#actividad_"+id_actividad).removeClass("hiddenStructure");
    
}
function cancelar(){
     ocultar_todo();
     $("#nombre_nueva_actividad").val("");
     $("#nombre_antiguo").val("");
     $("#peso_nueva_actividad").val("");
     $("#descripcion_nueva_actividad").val("");
     $("#nueva").val("si");
     mostrar_tabla();
}
  

  
function eliminar(){
   $("#eliminar").val("eliminar");
   document.getElementById("factividad_form").submit(); 
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
    nota.setAttribute("type", "number");
    nota.setAttribute("min", "0");
    nota.setAttribute("max", "10");
    nota.setAttribute("step", "0.01");
    // placeholder="Nota" class="Centrar form-normal form-control"
    nota.setAttribute("placeholder", "Nota");
    nota.setAttribute("class", "Centrar form-normal form-control validate[number,max[10]]");
    var foo = document.getElementById("nota_dinamica");
 
    //Append the element in page (in span).
    foo.appendChild(nota);
    
     //Create an input type dynamically.
    var obs = document.createElement("textarea");
 
    //Assign different attributes to the element.
    obs.setAttribute("cols", "30");
    obs.setAttribute("rows", "50");  
    obs.setAttribute("name", "obs_"+id_asignatura+"_"+id_alumno+"_"+actividad);
    obs.setAttribute("id", "obs_"+id_asignatura+"_"+id_alumno+"_"+actividad_remplazado);
    obs.setAttribute("placeholder", "Observaciones");
    obs.setAttribute("class", "Centrar form-text-area form-control");
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
        dialogClass: "no-close",
        width:400,
        height:450, 
        open: function() {
            $("#dinamic_form").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        },
         buttons: {
            Guardar: function() {
                if(copiar_y_borrar()){
                mostrar_actualizar();
                $( this ).dialog( "close" );
            }
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
    $("#actualizar").removeClass("hiddenStructure2");
    //$("#actualizar").addClass("mostrarActualizar");
    

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
if(fnOnSubmit()){
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
return true;
}
return false;
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
    document.exportar_form.submit();
});
$("#importar").click(function(){
    document.importar_form.submit();
});

function editar_actividad(nombre,peso, descripcion){
    $("#nombre_nueva_actividad").val(nombre);
    $("#nombre_antiguo").val(nombre);
    $("#peso_nueva_actividad").val(peso*100);
    $("#descripcion_nueva_actividad").val(descripcion);
    $("#nueva").val("no");
    nueva_actividad();
}
 function fnOnSubmit(){
            var nota = document.getElementById("nota_dinamica").children[0];
            console.log(nota.value);
            if(isNaN(nota.value)|| nota.value == "" || nota.value<0 || nota.value > 10){ alert("La nota debe ser un número entre 0 y 10");return false;
            }else{
                return true;
            }
            
            }
</script>
<?php $view['slots']->stop(); ?>
