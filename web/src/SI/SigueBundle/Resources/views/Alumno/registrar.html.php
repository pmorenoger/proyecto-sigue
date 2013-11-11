<?php $view->extend('::layout.html.php') ?>
<?php $view['slots']->set('rol', 'Alumno '.$alumno->getNombre()); ?>

<?php $view['slots']->start("center"); ?>
<div class="perfil">
    <div class="encabezado3">
        <h3>Asignatura  <?php echo $asignatura->getNombre(); ?></h3> 
    </div>
    
    <div id="accordion-resizer" class="ui-widget-content">
        <div id="accordion">
            <h3>Registar un nuevo TOKEN</h3>
                <div>
                    <form enctype="multipart/form-data" id="registrar_token" action="<?php echo $view['router']->generate('si_sigue_alumno_token',array("id"=>$alumno->getIdalumno(),"asig"=>$asignatura->getId())); ?>" method="POST">
                        <label for="token" class="labelToken">TOKEN: </label>
                        <input type="text" name="codigo" placeholder='Código' class='validate[required]'/>
                        <input type="submit" value="Registrar" id="bRegistrar" />
                    </form>
                    
                    <?php if($res == 2): ?>
                        <div id="tooltip_exito">
                            <p>TOKEN REGISTRADO CORRECTAMENTE</p>
                        </div>
                    <?php elseif ($res == 1): ?>
                        <div id="tooltip_exito">
                            <p>EL TOKEN ES INVÁLIDO</p>
                            <p>Debes de ingresas un código registrado por el profesor y que no sea repetido. </p>
                        </div>
                    <?php endif;?>
                    
                </div>
            <h3>Ver estadísticas</h3>
                <div>
                    
                </div>
        </div>
    </div>
</div> 
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
        
        $("#bRegistrar").submit(fnOnSubmit);
        $("#registrar_token").validationEngine('attach',{relative: true,promptPosition: "bottomRight"});
        function fnOnSubmit(){
                if (!$("#bRegistrar").validationEngine('validate')) return false;
                return true;
            }
    });
</script>
<?php $view['slots']->stop(); ?>
