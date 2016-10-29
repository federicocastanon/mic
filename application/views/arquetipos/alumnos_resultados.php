<div class="container">
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
	   <div class="page-header tituloWrapper">
	       <h1 class="pull-left titulo"><?= $ejercicio->consigna ?></h1>
           <?php if ($this->template_type == 'admin'): ?>
               <a class="btn btn-large pull-right" href="<?php echo base_url('/arquetipos')?>"><i class="icon-arrow-left"></i> Volver</a>
           <?php else: ?>
               <a class="btn btn-large pull-right" href="<?php echo base_url('/')?>"><i class="icon-arrow-left"></i> Volver</a>
           <?php endif; ?>

        </div>
    </div>
    <div class='row-fluid'>
        <div class="span12 public">
            <?php
            foreach ($ejercicio->preguntas as $pregunta): ?>
            <p><strong><?= $pregunta->pregunta ?></strong></p>
            <?php endforeach;  ?>
        </div>
    </div>
    <div class='row-fluid'>
        <div class="span12 public">
            <a class="btn btn-small btn-primary pull-left"
               href='<?php echo base_url('/arquetipos/alumno_ejercicio/' . $ejercicio->id)?>'>
                <i class="icon-list-alt"></i> Responder
            </a>
    		<a class="btn pull-right" href='#' onClick='window.print();'><i class="icon-print"></i> imprimir</a><br>

    	</div>
    </div>
    <?php foreach ($imagenes as $imagen_id => $imagen): ?>
        <div class="publicwell">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><h4><?= $imagen['titulo'] ?></h4></td>
                    <td width="40%"><img src="<?= $imagen['url']?>" alt='foto de <?= $imagen['titulo']?>' width='280'></td>
                </tr>
            </table>
        </div>

        <div class="row-fluid">
                <div class="span6">

                    <?php
                    foreach ($ejercicio->preguntas as $pregunta): ?>
                        <div style="float: left; width: 100%"><strong><?= $pregunta->pregunta ?></strong></div>
                        <?php
                        if (isset($respuestas[$imagen_id][$pregunta->id])){
                            foreach ($respuestas[$imagen_id][$pregunta->id] as $resp): ?>
                                <div  style="float: left; width:100%; min-height: 40px; color: <?php if($resp->publico){?> blue <?php }else{?> green <?php }?>">
                                    <div style="width:100%;  float: left">  <?= $resp->respuesta ?> </div>
                                </div>
                            <?php endforeach; } ?>
                    <?php endforeach;  ?>
                </div>

        </div>
    <?php endforeach //imagenes ?>

	<footer id="footer">
        <p class="pull-right"><a href="#top">Arriba</a></p>       
	</footer>    
</div>
