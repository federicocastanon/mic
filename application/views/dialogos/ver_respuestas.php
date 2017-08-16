<div class="container">
	<section>
	    <div class="row-fluid">
	        <div class="page-header">
	            <h1>Detalle</h1>
	        </div>
	        <div class="span12">
				<table class='table table-striped'> 
					<thead>
						<th>Fecha</th> 
						<? if (!$alumno_id): ?>
							<th>Alumno</th>
						<? endif ?>
						<th>Planteo</th> 
						<th>Valoración</th>
						<th>Fecha </th>
						<th>Acciones</th> 
					</thead>
					<tbody>
						<?foreach ($respuestas as $r) : ?>
							<tr>
								<td><?= $r->created_at ?></td>
								<? if (!$alumno_id): ?>
									<td><?= $r->nombre ?></td>
								<? endif ?>
								<td><?= $r->campo_1 ?></td>
								<td>
									<? if ($r->calificacion): ?> 
										<div class="circ<?= $r->calificacion ?>">.</div>
									<? endif ?>
								</td>
								<td><?= $r->calificacion_cuando ?></td>
								<td>
									<?php if (!$r->calificacion_cuando) :?>
										<a href='#modalCalificar_<?= $r->id ?>' data-toggle='modal' class='btn'>calificar</a>
									<?php endif ?>
								</td>
							</tr>
							<div class="modal hide fade" id="modalCalificar_<?= $r->id ?>">
							  <div class="modal-header">
							    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							    <h3>Calificar</h3>
							  </div>
							  <div class="modal-body">
							  	<form action='<?= base_url('/dialogos/calificar') ?>' method='post'> 
							  		<input type='hidden' name='id' value='<?= $r->id ?>'>
							  		<div class="control-group">
							    		<textarea name='texto' rows="4" class="devolucion" placeholder="Texto de devolución para el alumno"></textarea>
							    	</div>
							    	<div class="control-group circulos">
							    			<label class='radio inline circrojo'>
	  											<input type="radio" name='status' value="rojo"> 
	  										</label>
	  										<label class='radio inline circamarillo'>
	  											<input type="radio" name='status' value="amarillo">
	  										</label>
	  										<label class='radio inline circverde'>
	  											<input type="radio" name='status' value="verde">
	  										</label> 
									</div>
									<div class="control-group">
										<label class="checkbox">
											<input type='checkbox' value='1' name='finalizar'> Finalizar
										</label>
									</div>
									<div class="control-group">
										<input class="btn pull-right" type='submit' value='aceptar'>
									</div>
								</form>
							  </div>
							</div>
						<?php endforeach ?>
					</tbody>
				</table> 
			</div>
		</div>
	</section>
</div>
<script>
$(function() {
	$(".circulos input").click(function() {
		$(".circulos input").parent('label').removeClass('checked');
		$(".circulos input:checked").parent('label').addClass('checked');
	});
});
</script> 