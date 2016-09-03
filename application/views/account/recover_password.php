	<?php echo validation_errors(); ?>
	<form class="form-horizontal" method='post'>
    
	<div class="page-header">
		<h2 class="form-heading">Por favor ingrese una nueva contraseña</h2>
	</div>

		<div class="control-group">
			<label class="control-label" for="inputPassword">Clave</label>
			<div class="controls">
				<input type="password" value="<?php echo set_value('password');?>" name='password' id="inputPassword">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputPasswordConf">Confirmación de clave</label>
			<div class="controls">
				<input type="password" value="<?php echo set_value('password_confirmation');?>" name ='password_confirmation' id="inputPasswordConf">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-success">Cambiar clave</button>
			</div>
		</div>	
	</form>	