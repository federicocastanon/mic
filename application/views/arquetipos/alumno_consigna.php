<? if (!@$ejercicio->consigna): ?>
<h1>Ejercicio no existente</h1>
<? return;endif ?> 


<div class="row-fluid publichead">

    <div class="span10">
<div>
<img src="<?= assets_url('/img/foco_public.png')?>" width="76" height="92" border="0">
<span class="logosmall">Focos</span> <span class="logolightsmall"> en juego</span></div>
    </div>
    <div class="span2">
        <div class="pull-right"><a href="http://citep.rec.uba.ar" target="_blank">
        <img src="<?= assets_url('/img/citep-mic-web.png')?>" width="110" height="54" border="0">
        </a></div>
    </div>

</div>

<div class="row-fluid">
	<div class="page-header">
	  <h2><?php echo $ejercicio->consigna ?></h2> 
      </div>
	<div class="focos_well">
	  <?= $ejercicio->desarrollo ?>
</div>
</div>


  <div class="row-fluid">
	<div class="span5"></div>
    <div class="span2">
	<a href='<?= base_url("/arquetipos/alumno_ejercicio/$hash")?>' class='btn btn-large'><i class="fa fa-pencil-square-o fa-lg"></i> Ir al ejercicio</a>
	</div>
    <div class="span5"></div>
</div>