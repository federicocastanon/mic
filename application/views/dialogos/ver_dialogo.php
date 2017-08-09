<script type='text/javascript'>


    $(window).load(function(){
        $("#dialogo").animate({scrollTop: $("#dialogo").prop("scrollHeight")},1000);
    });

    function irUltimoMensaje(){
        $("#dialogo").animate({scrollTop: $("#dialogo").prop("scrollHeight")},1000);
    }

    function crearIntervencion(data){
        var html ='<div class="col-md-12 top30" >';
        if(data.tipo == 1){
            html += '<div class="col-sm-6 panelBlanco" style="float: left;">';
            html += data.texto ;
            html += '</div>';
            html += '<div class="col-md-12 fechaIntervencion" style="text-align: left">';
            html +=  data.fecha ;
            html += '</div>';
        }else if(data.tipo == 2){
            html += '<div class="col-sm-6 panelVerde" style="text-align: right">';
            html += data.texto;
            html += '</div>';
            html += '<div class="col-md-12 fechaIntervencion" style="float: right">';
            html +=  data.fecha ;
            html += '</div>';
        }else if (data.tipo == 3){
            html += '<div class="col-sm-12" ><div class="panelGris">';
            html += data.texto + data.fecha + '</div>';
        }

        html += '</div>';
        return html;
    }

    setInterval(recargaAjax, 5000);

    function recargaAjax(){
    var ultimoId = $('#ultimoId').val();
    var url = "<?php echo base_url('/dialogo/recargaAjax/' . $dialogo->id)?>";
    var dialogoId = <?php echo $dialogo->id ?>;
    var test = '<?php echo  $_SESSION["alias"] ?>';
        $.ajax({
            url: url,
            method: "POST",
            dataType: 'json',
            data: {"dialogoId" : dialogoId, "ultimoId" : ultimoId},
            success: function(data){

                if(data.intervenciones.length > 0){

                    var htmlNuevo = '';
                    var nuevoUltimoId;
                    for (i = 0; i<data.intervenciones.length;i++) {
                        htmlNuevo += crearIntervencion(data.intervenciones[i]);
                        nuevoUltimoId = data.intervenciones[i].id;
                    }
                    $('#dialogo').append(htmlNuevo);
                    $('#ultimoId').val(nuevoUltimoId);
                }


            },
            error: function(){
                console.log('ERROR en recarga');
            }
        });

    }

    function intervenirAjax(){
        var ultimoId = $('#ultimoId').val();
        var nuevaIntervencion = $('#nuevaIntervencion').val();
        var url = '<?php echo base_url('/dialogo/intervenirAjax/' . $dialogo->id)?>';
        var dialogoId = $('#ultimoId').attr('dialogoId');
        $('#nuevaIntervencion').val('');
        $.ajax({
            url: url,
            method: "POST",
            dataType: 'json',
            data: {"dialogoId" : dialogoId, "ultimoId" : ultimoId, "intervencion" : nuevaIntervencion},
            success: function(data){
                if(data.intervenciones.length > 0){

                    var htmlNuevo = '';
                    var nuevoUltimoId;
                    for (i = 0; i<data.intervenciones.length;i++) {
                        htmlNuevo += crearIntervencion(data.intervenciones[i]);
                        nuevoUltimoId = data.intervenciones[i].id;
                    }
                    $('#dialogo').append(htmlNuevo);
                    irUltimoMensaje();
                    $('#ultimoId').val(nuevoUltimoId);
                }


            },
            error: function(){
                console.log('ERROR en intervencion');
            }
        });

    }

</script>
<link href="<?php echo assets_url('plugins/star-rating/css/star-rating.css')?>" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo assets_url('css/jquery-ui.css')?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?php echo assets_url('plugins/star-rating/js/star-rating.js')?>" type="text/javascript"></script>
<script src="<?php echo assets_url('plugins/star-rating/js/locales/es.js')?>"></script>
<script src="<?php echo assets_url('js/jquery-ui.js')?>"></script>
<style>
    .caption{
        width: 100%;
    }
</style>
<div class="col-md-8">
    <div class="col-md-12">
        Estás actuando como <h4 class="celeste"><?php echo  $_SESSION["alias"] ?></h4>
    </div>
    <div class="spacer"></div>
<?php if ($_SESSION["alias"] == $dialogo->evaluado or $_SESSION["alias"] == $dialogo->secundario ): ?>

    <div class="modal fade" id="modal-container-838576" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Vas a abandonar el diálogo y otro podrá tomar tu lugar
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Seguir la charla
                    </button>
                    <a class="btn btn-lg btn-info" href="<?php echo base_url('/dialogo/levantarse/'. $dialogo->id)?>">LEVANTARSE</a>
                </div>
            </div>

        </div>

    </div>
    <div class="modal fade" id="modal-container-838577" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        Darás por concluido el diálogo. De todas formas podrás retomarlo más adelante
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Seguir la charla
                    </button>
                    <a class="btn btn-lg btn-danger" href="<?php echo base_url('/dialogo/terminar/'. $dialogo->id)?>">TERMINAR DIÁLOGO</a>
                </div>
            </div>

        </div>

    </div>

<?php endif; ?>




<div class="col-md-12">
    Situación: <h4 class="celeste"> <?php echo $prisma->descripcion ?></h4>
</div>
<input type="hidden" id="parametros" dialogoId="" url="">

    <?php if (isset($intervenciones) && $intervenciones): ?>
<div id="dialogo" class="col-md-12">
        <?php
        $rc = -1;
        foreach ($intervenciones as $e):
        $rc++;?>
        <div class="col-md-12 top30" >
            <?php if ($e->tipo == 1): ?>
               <div class="col-md-6 panelBlanco" style="float: left;">
                   <?php echo $e->texto ?><br>
               </div>
                <div class="col-md-12 fechaIntervencion" style="text-align: left">
                    <?php echo $e->fecha ?>
                </div>
            <?php elseif ($e->tipo == 2): ?>
               <div class="col-md-6 panelVerde" style="float: right">
                   <?php echo $e->texto ?><br>
               </div>
                <div class="col-md-12 fechaIntervencion" style="text-align: right">
                    <?php echo $e->fecha ?>
                </div>
            <?php elseif ($e->tipo == 3): ?>
               <div class="col-md-12" >
                   <div class="panelGris">
                       <?php echo $e->texto ?>
                       <i><?php echo $e->fecha ?></i>
                   </div>
               </div>
            <?php endif; ?>

        </div>
        <?php endforeach ?>
   </div>
            <input type="hidden" id="ultimoId" value="<?php echo $intervenciones[$rc]->id ?>">
    <?php else: ?>
  <div class="col-md-12">
    <h1>Este dialogo no empezó</h1>
  </div>
    <?php endif; ?>

    </div>
    <div class="col-md-4">
        <!-- Columna de calificacion-->
        <?php if ($_SESSION["alias"] != $dialogo->evaluado and $_SESSION["alias"] != $dialogo->secundario and $dialogo->terminado): ?>

        <link href="<?php echo assets_url('plugins/star-rating/css/star-rating.css')?>" media="all" rel="stylesheet" type="text/css" />
            <script src="<?php echo assets_url('plugins/star-rating/js/star-rating.js')?>" type="text/javascript"></script>
            <script src="<?php echo assets_url('plugins/star-rating/js/locales/es.js')?>"></script>
            <form id="myForm" method='post' action='<?php echo base_url('/dialogo/calificar/' . $dialogo->id)?>'>
                <?php if (isset($evaluacion) ){ ?>
                    <div>
                        <h2>Ya calificaste este dialogo</h2>
                        <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/verCalificaciones/' . $prisma->id)?>"><i class="fa fa-arrow-left"></i> Volver</a>
                    </div>
                <?php }else if ( (!$dialogo->evaluado or !$dialogo->secundario) and !$dialogo->terminado){ ?>
                    <div>
                        <h2>No se puede calificar este dialogo pues al menos uno de los participantes está ausente y el dialogo no está terminado</h2>
                    </div>
                <?php }else{ ?>


                    <div class="form-group">
                        <label for="sugerencia">Sugerencia</label>
                        <input id="sugerencia" name="sugerencia">
                    </div>
                    <div class="form-group">
                        <label for="positiva">Valoración positiva</label>
                        <input id="positiva" name="positiva">
                    </div>
                    <div class="form-group">
                        <label for="aclaracion">Pedido de aclaración</label>
                        <input id="aclaracion" name="aclaracion">
                    </div>

                    <input id="input-id" name="calificacion"  >
                    <button type="submit" class="vinculo btn btn-default"> Calificar</button>
                <?php } ?>
            </form>
            <script type='text/javascript'>
                $(document).ready(function() {
                    $("#input-id").rating({ language:'es'});
                } );
            </script>

        <?php else: ?>

            <div class="col-md-12 ">
                <div class="col-md-6">
                    <div class="col-md-12 ">   <h4>Promedio de pares</h4></div>
                    <div class="col-md-12 ">  <input class="estrellas" name="calificacion" value="<?php echo $dialogo->promedio ?>" ></div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12 ">  <h4>Calificación docente</h4></div>
                    <div class="col-md-12 "> <input class="estrellas" name="calificacion" value="<?php echo $dialogo->evaluacion ?>" ></div>
                </div>
                <div class="spacer col-md-12">
                </div>

                <div class="col-md-12 ">
                    <div class="tabs">
                        <ul>
                            <li class="sugerencia pestania" ><a href="#tabs-1<?php echo $dialogo->id ?>">SUGERENCIAS</a></li>
                            <li class="positiva pestania" ><a href="#tabs-2<?php echo $dialogo->id ?>">Valoraciones Positivas</a></li>
                            <li class="aclaracion pestania" ><a href="#tabs-3<?php echo $dialogo->id ?>">Aclaraciones</a></li>
                        </ul>
                        <div id="tabs-1<?php echo $dialogo->id ?>">
                            <?php $rc = 0;
                            foreach ($dialogo->sugerencias as $s):
                                $rc++;
                                ?>
                                <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                            <?php endforeach ?>
                            <p style=" background-color: #D5D59D; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $dialogo->sugerencia?></p>
                        </div>
                        <div id="tabs-2<?php echo $dialogo->id ?>">
                            <?php $rc = 0;
                            foreach ($dialogo->positivos as $s):
                                $rc++;
                                ?>
                                <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                            <?php endforeach ?>
                            <p style=" background-color: #277415; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $dialogo->sugerencia?></p>
                        </div>
                        <div id="tabs-3<?php echo $dialogo->id ?>">
                            <?php $rc = 0;
                            foreach ($dialogo->aclaraciones as $s):
                                $rc++;
                                ?>
                                <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                            <?php endforeach ?>
                            <p style=" background-color: #66FF66; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $dialogo->sugerencia?></p>
                        </div>
                    </div>
                </div>
            </div>

            <script type='text/javascript'>
                $(document).ready(function() {
                    $(".estrellas").each(function(){
                        $(this).rating({ language:'es', readonly: true, size: 'xxs', showClear : false});
                    });
                    $( ".tabs" ).each(function(){
                        $(this).tabs({
                            collapsible: true,
                            active: true
                        });
                    });
                } );
            </script>

        <?php endif; ?>

    </div>
    <div class="col-md-12 spacer"></div>
    <div class="col-md-12">
        <?php if ($_SESSION["alias"] == $dialogo->evaluado or $_SESSION["alias"] == $dialogo->secundario ): ?>
            <form id="myForm" method='post' action='<?php echo base_url('/dialogo/intervenir/' . $dialogo->id)?>'>
                <input type="hidden" name="dialogoId" id="dialogoId">
                <input type="hidden" name="profesional" id="profesional">
                <div class="col-md-7">
                    <textarea style="width: 100%" name="intervencion" id="nuevaIntervencion" placeholder="Escribí acá...." required="true"></textarea>
                </div>
                <div class="col-md-1">
                    <span class="vinculo btn btn-default" onclick="intervenirAjax()">ENVIAR</span>
                </div>

            </form>
        <?php endif; ?>
        <div class="col-md-2">
            <a id="modal-838576" href="#modal-container-838576" role="button"  data-toggle="modal" class="botonesDialogo levantarse"><i class="fa fa-sign-out negro" aria-hidden="true"></i>Levantarse</a>
        </div>
        <div class="col-md-2">
            <a id="modal-838577" href="#modal-container-838577" role="button"  data-toggle="modal" class="botonesDialogo terminarDialogo"><i class="fa fa-window-close-o negro" aria-hidden="true"></i> Terminar Dialogo</a>
        </div>
    </div>
