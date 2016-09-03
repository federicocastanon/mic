<section>
<div class="row-fluid">
	  <h2><?= $proyecto->nombre ?></h2> 
</div>
	<div class='row-fluid'> 
		<? if ($colaboradores): ?>
		<div class='span5'> 
			<form method='post'>
				<fieldset> 
					<legend>Actuales colaboradores</legend>
					<ul>
						<? foreach ($colaboradores as $c): ?>
						<li>
                        	<input type='checkbox' name='colaborador[<?= urlencode($c->id) ?>]' />
							<?= $c->name ?> (<?= $c->email?>)
						</li> 
						<? endforeach ?>
					</ul>
                    <br>
					<button class='btn btn-danger pull-right'><i class="fa fa-trash-o"></i> Borrar seleccionados</button> 
				</fieldset>
			</form> 
		</div> 
		<? endif ?> 
        
        <div class='span2'> </div> 
        
        
		<div class='span5'> 
			<form class='form-horizontal' method='post' action='<?=base_url("/proyectos/invitar/$proyecto_id")?>'>
				<?php if (isset($error)) echo $error . '<br />'?>
				<legend>Agregar colaboradores</legend>
				<div class="control-group">
					<label class="control-label colaboradores" for="inputEmail">Colaboradores</label>
					<div class="controls">
						<textarea name='colaboradores'></textarea> 
					</div>
					<span class="help-inline help-colab">Formato: Nombre, Apellido, Email</span>
				</div>
				<div class="control-group">
					<label class="control-label colaboradores" for="inputEmail">Mensaje a enviar</label>
					<div class="controls">
						<textarea name='mensaje'></textarea> 
					</div>
					<span class="help-inline help-colab">Opcional, dejar vacio para no enviar mensaje personalizado.</span>
				</div>
                 <br>
				<button class='btn pull-right'><i class="fa fa-user"></i> Agregar colaboradores</button> 
			</form>
			

		</div> 
	</div> 
</section>