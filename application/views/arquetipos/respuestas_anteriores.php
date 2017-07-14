
<div class="col-md-12">
    <h2><?php echo $ejercicio->consigna ?></h2>
</div>
<div class="col-md-12">
    <h4><?php echo $ejercicio->desarrollo ?></h4>

    <div class="spacer"></div>
</div>

<div class="col-md-12">
    <div class="col-md-3">
        <b>Alias: </b> <?= $alias?>
    </div>
    <div class="col-md-2">
        <a class="cambiarAlias" href="<?php echo base_url('/arquetipos/cambiarAlias/' . $ejercicio->id)?>"><i class="fa fa-refresh" aria-hidden="true"></i> Cambiar Alias</a>
    </div>
    <div class="col-md-7 botonera">
        <a class="btn btn-default pull-right" href="<?php echo base_url('/arquetipos/link_publico/' . $ejercicio->id)?>"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>
</div>


<?php  if (isset($respuestasAnteriores) && count($respuestasAnteriores)>0):?>

    <?php foreach ($imagenes as $i=>$img): ?>
        <div class="col-md-12 panelGrisClaro">
                <div class="col-md-12">
                    <h4 class="tituloFoco"><?php echo $img->titulo ?></h4>
                </div>
                <div class="col-md-3">
                    <img id="img_small_<?php echo $img->id?>" class='imagenModal'
                         src="<?php echo $img->imagen_ubicacion?>"  alt="">
                </div>
                <div class="col-md-9">
                <?php
                    foreach ($ejercicio->preguntas as $preg): ?>
                        <h5 class="preguntaFoco"><?= $preg->pregunta?></h5>
                    <?php    foreach ($respuestasAnteriores as $resp): ?>
                        <?php $noRespondio = true;
                            if ($resp->imagen_id == $img->id and $preg->id == $resp->pregunta_id){ $noRespondio = false; ?>
                            <div class="respuesta"> <?= $resp->respuesta ?>  </div>
                        <?php break;} ?>
                    <?php endforeach; ?>
                        <?php  if (isset($noRespondio) && $noRespondio):?>
                            <div class="respuesta"> <i>No Respondió esta pregunta</i></div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>
        </div>
    <?php endforeach ?>

<?php else: ?>
<div class="col-md-12"> <i> No hay respuestas anteriores para esta actividad. Asegúrese de haber ingresado el mismo alias que utilizó anteriormente.</i></div>
<?php endif; ?>