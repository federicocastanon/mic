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
	margin-top: -20px;
	z-index:1;
	position: relative;
	margin-left: 150px;
}


</style>

 <div id="logo"> 
 	<span class="logo">Prismas</span> 
	<span class="logolight"> 
		entramados<br>
    	<img src="<?= assets_url("/img/prismas.png") ?>" width="118" height="122">
	</span>
</div>
<div class="row">
	<div class="offset2 span8 offset2">
		<h2 class="consigna"><?= $ejercicio->consigna?></h2> 
	</div>
</div>
<br>
<div class="row">
	<div id="entrar" class="offset5 span2 offset5">
		<a href="<?php echo base_url("/dialogos/alumno_ejercicio/$hash") ?>" class="btn btn-large" type="button">ENTRAR</a>
	</div>
</div>


       <!-- CIRCULOS -->
<div id="circs">
	<img class="featurette-image pull-right" src="<?= assets_url("/img/bg_prismas.png") ?>">
</div>

