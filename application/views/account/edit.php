<section>
	<?php echo validation_errors(); ?>
	<form class="form-horizontal" method='post'>
    
        <div class="page-header">
    <h2 class="form-heading">Mi usuario</h2>
    </div>

	<div class="control-group">
			<label class="control-label" for="inputEmail">Email</label>
			<div class="controls">
				<input type="text" value="<?php echo set_value('email', @$usuario->email)?>" name='email' 
					id="inputEmail" placeholder="email@ejemplo.com" disabled>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputName">Nombre</label>
			<div class="controls">
				<input type="text"  value="<?php echo set_value('name', @$usuario->name)?>" name='name' id="inputName" 
					placeholder="Nombre">
			</div>
		</div>
		<div class='row'>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary">Modificar</button>
					<a href="<?php echo base_url('/admin/')?>" class="btn">Cancelar</a>
				</div>
			</div>	
		</div>
	</form>
</section>