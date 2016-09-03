<section>
<div class="page-header">
<h1><?= $proyecto->nombre ?></h1> 
<a class="btn  pull-right" onClick='window.print();return false;' href='#'>
        <i class="icon-print">
        </i>
        imprimir
      </a>      
</div>
	<?
	foreach ($secciones as $seccion=>$subsecciones):
	?>
		<h2><?=ucfirst($seccion) ?></h2> 
		<? foreach ($subsecciones as $i=>$subseccion): ?>
			<div class='ficha' data-id='<?=$subseccion->id?>'>
				<div class='row-fluid fichamargen'> 
					<span class="subsec"><?= $subseccion->nombre ?> </span>
					<a href='<?=base_url("/proyectos/editar_subseccion/" . $subseccion->id)?>' class='btn'><i class='icon-pencil'></i> </a>
					
					<a href='#' class='ocultar btn' data-id='<?=$subseccion->id?>' alt='Ocultar sección'><i class='icon-remove'></i> </a></div>

				<? if ($subseccion->tipo == 'texto') : ?>
					<div class='row'> 
						<div class='span12 well' style='min-height:100px;'>
							<?= $subseccion->contenido ?>
						</div>
					</div>
				<? endif ?> 
			</div>

		<? endforeach ?>
	<? endforeach ?>
</section> 
<script>
$(function () {
	$(".ocultar").click(function() {
		$(".ficha[data-id=" + $(this).data('id') + "]").hide();
	});

});
</script>
