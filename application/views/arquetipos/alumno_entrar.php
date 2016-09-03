<? if (!@$ejercicio->consigna): ?>
<h1>Ejercicio no existente</h1>
<? return;endif ?> 
<style type="text/css">
body {
	padding-top: 50px;
	padding-bottom: 40px;
	background-image: url(../../../assets/img/bg.jpg);
	background-repeat: no-repeat;
	background-position: center top;
	overflow-y:hidden!important;
}
div#entrar{
	z-index:2;
	position: relative;
}
div#circs img{
	margin-top: -130px;
	z-index:1;
	position: relative;
}
</style>

<div id="logo">
	<span class="logo">
		Focos</span> <span class="logolight"> en juego<br>
		<img src="<?php echo assets_url('/img/foco.png'); ?>" width="116" height="120">
	</span>
</div>
<div class="row">
	<div id="entrar" class="offset1 span10 offset1">
		<h2><?php echo $ejercicio->consigna ?></h2> 
	</div>
</div>
<br>
<div class="row">
	<div id="entrar" class="offset5 span2 offset5">
		<a href="<?php echo base_url("/arquetipos/alumno_consigna/$hash") ?>" class="btn btn-large" type="button">ENTRAR</a>
	</div>
</div>

       <!-- CIRCULOS -->
<div id="circs">
<img class="featurette-image pull-right" src="../../../assets/img/bg_alumno.png">
</div>
