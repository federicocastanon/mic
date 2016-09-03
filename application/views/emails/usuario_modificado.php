<p>
	Su cuenta en CITEP MIC ha sido modificada.
	<br /><br />
	<? if ($agregados): ?>
		Permisos asignados: <?= implode(',', $agregados) ?>
		<br /><br />
	<? endif ?>
	<? if ($quitados): ?>
		Permisos desasignados: <?= implode(',', $quitados) ?>
		<br /><br />
	<? endif ?>
	<br /> 
	Por cualquier consulta, contáctate con nosotros a infomic@citep.rec.uba.ar
</p>