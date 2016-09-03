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
	   <div class="page-header">
	       <h1><?= $ejercicio->consigna ?></h1>
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
            <a class="btn pull-left" href='#' onClick=''> Responder</a><br>
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
                    if (isset($respuestas[$imagen_id])){
                    foreach ($respuestas[$imagen_id] as $resp): ?>
                        <p><strong><?= $resp->pregunta ?></strong></p>
                        <p><?= $resp->respuesta ?></p>
                    <?php endforeach; } ?>
                </div>

        </div>
    <?php endforeach //imagenes ?>

	<footer id="footer">
        <p class="pull-right"><a href="#top">Arriba</a></p>       
	</footer>    
</div>
