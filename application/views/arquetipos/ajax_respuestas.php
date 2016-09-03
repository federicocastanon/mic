<?foreach ($respuestas as $imagen) : ?>
	<div> 
		<div class='pull-left' style='width:150px'>
			<img src="<?php echo $imagen[0]->imagen_ubicacion ?>" width='150px' height='150px' />
			<?php echo $imagen[0]->imagen_titulo ?>
		</div>
		<div class='pull-left'> 
			<ul class='preguntasyrespuestas'>
				<?php foreach ($imagen as $r): ?>
					<li class='pregunta'><?php echo $r->pregunta ?></li> 
					<li class='respuesta'><?php echo $r->respuesta ?></li>
				<?php endforeach ?>
			</ul>
		</div>
	</div> 
	<br clear='both' /><br clear='both' />
<?php endforeach ?>
