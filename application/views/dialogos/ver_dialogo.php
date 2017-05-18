
<div class="row-fluid">
    <?php if ($_SESSION["alias"] != $dialogo->evaluado and $_SESSION["alias"] != $dialogo->secundario ): ?>
        <div class="span12">
            <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/lobbyDialogos/' . $dialogo->prisma)?>"><i class="fa fa-arrow-left"></i> Volver</a>
       </div>
    <?php endif; ?>
    </div>

    <div class="spacer"></div>
<?php if ($_SESSION["alias"] == $dialogo->evaluado or $_SESSION["alias"] == $dialogo->secundario ): ?>
    <p><i>Estás actuando como <b><?php echo  $_SESSION["alias"] ?> </b></i>
        <a id="modal-838576" href="#modal-container-838576" role="button" class="btn btn-lg btn-info" data-toggle="modal">LEVANTARSE</a>
        <a id="modal-838577" href="#modal-container-838577" role="button" class="btn btn-lg btn-danger" data-toggle="modal">TERMINAR DIÁLOGO</a>

        </p>

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




<div id="ret">
    <p><b>Situación:</b>  <?php echo $prisma->descripcion ?></p>
</div>
    <?php if (isset($intervenciones) && $intervenciones): ?>
        <?php foreach ($intervenciones as $e): ?>
        <div class="row top30">
            <?php if ($e->profesional): ?>
                <div class="col-sm-6 panelAzul" style="float: left;">
            <?php else: ?>
                <div class="col-sm-6 panelMarron" style="float: right">
            <?php endif; ?>
                <?php echo $e->texto ?><br>
                <i><?php echo $e->fecha ?></i>
            </div>
        </div>
        <?php endforeach ?>

    <?php else: ?>
        <h1>Este dialogo no empezó</h1>
    <?php endif; ?>
            <?php if ($_SESSION["alias"] == $dialogo->evaluado or $_SESSION["alias"] == $dialogo->secundario ): ?>
                <form id="myForm" method='post' action='<?php echo base_url('/dialogo/intervenir/' . $dialogo->id)?>'>
                    <input type="hidden" name="dialogoId" id="dialogoId">
                    <input type="hidden" name="profesional" id="profesional">
                    <?php if ($_SESSION["alias"] == $dialogo->evaluado): ?>
                    <div class="col-sm-6 " style="float: left;">
                        <?php else: ?>
                        <div class="col-sm-6 " style="float: right">
                            <?php endif; ?>
                            <div class="spacer"></div>
                            <div class="spacer"></div>
                            <textarea style="width: 100%" name="intervencion" placeholder="Escribí acá...." required="true"></textarea>
                            <button type="submit" class="vinculo btn btn-lg btn-default"> INTERVENIR</button>
                            <a class="btn btn-lg btn-success" href="<?php echo base_url('/dialogo/calificar/'. $dialogo->id)?>">RECARGAR</a>
                        </div>
                </form>
            <?php endif; ?>

            <div class="spacer"></div> <div class="spacer"></div> <div class="spacer"></div>
<?php if ($_SESSION["alias"] != $dialogo->evaluado and $_SESSION["alias"] != $dialogo->secundario ): ?>

            <link href="<?php echo assets_url('plugins/star-rating/css/star-rating.css')?>" media="all" rel="stylesheet" type="text/css" />
            <script src="<?php echo assets_url('plugins/star-rating/js/star-rating.js')?>" type="text/javascript"></script>
            <script src="<?php echo assets_url('plugins/star-rating/js/locales/es.js')?>"></script>
            <form id="myForm" method='post' action='<?php echo base_url('/dialogo/calificar/' . $dialogo->id)?>'>
                <?php if (isset($evaluacion) ){ ?>
                    <div>
                        <h2>Ya calificaste este dialogo</h2>
                    </div>
                <?php }else if ( !$dialogo->evaluado or !$dialogo->secundario){ ?>
                    <div>
                        <h2>No se puede calificar este dialogo pues al menos uno de los participantes está ausente y el dialogo no está terminado</h2>
                    </div>
                <?php }else{ ?>
                    <?php if ($this->template_type != 'admin'  ): ?>

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

                    <?php endif; ?>
                    <input id="input-id" name="calificacion"  >
                    <button type="submit" class="vinculo"> Calificar</button>
                <?php } ?>
            </form>
            <script type='text/javascript'>
                $(document).ready(function() {
                    $("#input-id").rating({ language:'es'});
                } );
            </script>
<?php endif; ?>
