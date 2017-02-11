<link href="<?php echo assets_url('plugins/star-rating/css/star-rating.css')?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?php echo assets_url('plugins/star-rating/js/star-rating.js')?>" type="text/javascript"></script>
<script src="<?php echo assets_url('plugins/star-rating/js/locales/es.js')?>"></script>

<div class="row-fluid">
    <div class="span12">
        <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/lobbyDialogos/' . $prismaId)?>"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>

</div>
<?php if (isset($dialogos) && $dialogos): ?>
    <div class="row top30">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <th>Profesional</th>
                <th>Secundario</th>
                <th>Tu puntaje</th>
                <th>Promedio de pares</th>
                <th>Calificación docente</th>
                </thead>
                <tbody>
                <?php foreach ($dialogos as $e): ?>
                    <tr>
                        <td>
                            <?php echo $e->evaluado ?>
                        </td>
                        <td>
                            <?php echo $e->secundario ?>
                        </td>
                        <td>
                            <input class="estrellas" name="calificacion" value="<?php echo $e->tuPuntaje ?>" >
                        </td>
                        <td>
                            <input class="estrellas" name="calificacion" value="<?php echo $e->promedio ?>" >
                        </td>
                        <td>
                            <input class="estrellas" name="calificacion" value="<?php echo $e->evaluacion ?>" >
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif ?>
<script type='text/javascript'>
    $(document).ready(function() {
        $(".estrellas").each(function(){
            $(this).rating({ language:'es', readonly: true, size: 'xs', showClear : false});
        });
    } );
</script>