<section>
	<h2>Cambiar mi contraseña</h2>
	<hr>
	<span>Por favor ingrese su contraseña original y la nueva.</span>
	<br /><br />
	<?php echo validation_errors(); ?>
	<?php if (isset($extra_errors)) echo $extra_errors; ?>
	<form class="form-horizontal" method='post'>
		<div class="control-group">
			<label class="control-label" for="inputPasswordOrig">Contraseña original</label>
			<div class="controls">
				<input type="password" name='passwordOrig' id="inputPasswordOrig" >
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPassword">Nueva contraseña</label>
			<div class="controls">
				<input type="password" name='password' id="inputPassword" >
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPasswordConf">Confirme nueva contraseña</label>
			<div class="controls">
				<input type="password" name='passwordConf' id="inputPasswordConf" >
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-success">Enviar</button>
			</div>
		</div>	
	</form>	
</section>