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


    <div class="col-md-12">
        <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/recepcionPrisma/' . $prismaId)?>"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>
    <div class="col-md-12 top30">
<?php if (isset($dialogos) && $dialogos): ?>
    <div class="col-md-12 top30">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <th>id</th>
                <th>Profesional</th>
                <th>Secundario</th>
                <?php if($this->template_type != 'admin'): ?>
                <th>Tu opinión</th>
                <?php endif; ?>
                <th>Valoraciones de pares</th>
                <th>Valoración docente</th>
                <th>Acciones</th>
                </thead>
                <tbody>
                <?php foreach ($dialogos as $e): ?>
                    <tr class="filaCalificacion">
                        <td>
                            <?php echo $e->id ?>
                        </td>
                        <td>
                            <?php echo $e->evaluado ?>
                        </td>
                        <td>
                            <?php echo $e->secundario ?>
                        </td>
                        <?php if($this->template_type != 'admin'): ?>
                        <td><?php if($e->tuPuntaje > 0){ ?>
                                <input class="estrellas" name="calificacion" value="<?php echo $e->tuPuntaje ?>" >
                            <?php }else { ?>
                                <a class="btn btn-sm btn-warning pull-left" href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">CALIFICAR</a>
                            <?php } ?>
                        </td>
                        <?php endif; ?>
                        <td>
                            <input class="estrellas" name="calificacion" value="<?php echo $e->promedio ?>" >
                        </td>
                        <td>
                            <input class="estrellas" name="calificacion" value="<?php echo $e->evaluacion ?>" >
                        </td>
                        <td>
                            <a class="btn btn-sm btn-success pull-left" href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Ver dialogo</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <div class="tabs">
                                <ul>
                                    <li class="sugerencia" style="color: white"><a href="#tabs-1<?php echo $e->id ?>">SUGERENCIAS</a></li>
                                    <li class="positiva" style="color: white"><a href="#tabs-2<?php echo $e->id ?>">Valoraciones Positivas</a></li>
                                    <li class="aclaracion" style="color: white"><a href="#tabs-3<?php echo $e->id ?>">Aclaraciones</a></li>
                                </ul>
                                <div id="tabs-1<?php echo $e->id ?>">
                                    <?php $rc = 0;
                                    foreach ($e->sugerencias as $s):
                                        $rc++;
                                        ?>
                                        <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                                    <?php endforeach ?>
                                    <p style=" background-color: #D5D59D; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $e->sugerencia?></p>
                                </div>
                                <div id="tabs-2<?php echo $e->id ?>">
                                    <?php $rc = 0;
                                    foreach ($e->positivos as $s):
                                        $rc++;
                                        ?>
                                        <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                                    <?php endforeach ?>
                                    <p style=" background-color: #277415; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $e->sugerencia?></p>
                                </div>
                                <div id="tabs-3<?php echo $e->id ?>">
                                    <?php $rc = 0;
                                    foreach ($e->aclaraciones as $s):
                                        $rc++;
                                        ?>
                                        <p style=" background-color: <?php if($rc %2 == 1){?> #EFF0F1 <?php }else{?> #FFFFFF <?php }?>"><?php echo $s?></p>
                                    <?php endforeach ?>
                                    <p style=" background-color: #66FF66; color: #EFF0F1"><b>DOCENTE:</b> <?php echo $e->sugerencia?></p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
    <h3>Ningún dialogo ha terminado todavía</h3>
    <?php endif ?>
    </div>
<script type='text/javascript'>
    $(document).ready(function() {
        $(".estrellas").each(function(){
            $(this).rating({ language:'es', readonly: true, size: 'xxs', showClear : false});
        });
        $( ".tabs" ).each(function(){
            $(this).tabs({
                collapsible: true,
                active: false
            });
        });
    } );
</script>