<div class="row-fluid publichead">
    <div class="span10">
        <div>
            <img src="<?= assets_url('/img/foco_public.png') ?>" width="76" height="92">
            <span class="logosmall">Focos</span> <span class="logolightsmall"> en juego</span>
        </div>
    </div>
    <div class="span2">
        <div class="pull-right">
            <a href="http://citep.rec.uba.ar" target="_blank">
                <img src="<?= assets_url('/img/citep-mic-web.png')?>" width="110" height="54">
            </a>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12"><h2><?php echo $ejercicio->consigna ?></h2> </div>
</div>
<div class="row-fluid">
    <div class="span12"><b>Alias: </b> <i><?= $alias?></i>  </div>

</div>
<?php  if (isset($respuestasAnteriores) && count($respuestasAnteriores)>0):?>
<div class="container gallery">
    <?php foreach ($imagenes as $i=>$img): ?>
        <?php if ($i == 0 or $i == 3): ?>
            <div class="row">
        <?php endif ?>
        <div class="col-lg-4" style="width: 300px;">
            <a class="thumbnail" href="">
                <img <?php if (isset($respuestas[$img->id])): ?> class='grayscale' <?php endif;?> id='img_<?php echo $img->id?>'
                                                                                                  src="<?php echo $img->imagen_ubicacion?>" width='280px' height='280px' alt="" />
            </a>
            <p class="text-info"><?php echo $img->titulo ?></p>
            <?php
            if (isset($respuestasAnteriores)){
                foreach ($respuestasAnteriores as $resp): ?>
                    <?php if ($resp->imagen_id == $img->id): ?>
                        <div style="width:100%;  float: left"><?= $resp->pregunta ?><br><span style="color: blue"> <?= $resp->respuesta ?></span>   </div>
                    <?php endif ?>

                <?php endforeach; } ?>
        </div>
        <?php if ($i == 2 or $i == 5): ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>
<?php else: ?>
<div> <i> No hay respuestas anteriores para esta actividad. Asegúrese de haber ingresado el mismo alias que utilizó anteriormente.</i></div>
<?php endif; ?>