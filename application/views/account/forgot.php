	<h2><?php l('Olvide mi contraseña')?></h2>
	<hr>
	<span>Por favor ingrese su email y le enviaremos un link para que elija una nueva contraseña</span>
	<br /><br />
	<?php echo validation_errors(); ?>
	<form class="form-horizontal" method='post'>
		<div class="control-group">
			<label class="control-label" for="inputEmail">Email</label>
			<div class="controls">
				<input type="text" name='email' value="<?php echo set_value('email');?>" id="inputEmail" placeholder="alguien@ejemplo.com">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-success">Enviar</button>
			</div>
		</div>	
	</form>	