<?php 
	if (isset($usuario)) {
		$email = $usuario->email;
	} else { 
		$email = set_value('email', @$usuario->email);
	}
?>
<section>
	<?php echo validation_errors(); ?>
	<?php echo $custom_errors; ?>
	<form class="form-horizontal" method='post'>
    <div class="page-header">
    <h2 class="form-heading">Editar / Nuevo usuario</h2>
    </div>
    

		<div class="control-group">
			<label class="control-label" for="inputEmail">Email</label>
			<div class="controls">
				<input type="text" value="<?php echo $email?>" name='email' 
					id="inputEmail" placeholder="email@ejemplo.com" <?php if (isset($usuario)): ?> DISABLED <?php endif ?>>
			</div>
		</div>
		<?php if (!isset($usuario)) :?>
			<div class="control-group">
				<label class="control-label" for="inputPassword">Clave</label>
				<div class="controls">
					<input type="password" name='password' id="inputPassword">
				</div>
			</div>
		<?php endif;?>
		<div class="control-group">
			<label class="control-label" for="inputName">Nombre</label>
			<div class="controls">
				<input type="text"  value="<?php echo set_value('name', @$usuario->name)?>" name='name' id="inputName" 
					placeholder="Nombre">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputName">Permisos</label>
			<div class="controls">
				<?php foreach ($permisos as $p): 
					if ($p->id == 0) continue;
					$checked = false;
					if ($this->input->post()) { 
						$post_permiso = $this->input->post('permiso');
						if (isset($post_permiso[$p->id])) $checked = true;
					} else { 
						if (isset($permisos_usuarios)) { 
							foreach($permisos_usuarios as $pu) { 
								if ($pu->id == $p->id) { 
									$checked = true;
									break;
								}
							}
						}
					}
				?>
					<label class="checkbox">
						<input <?php if ($checked) echo 'CHECKED'?> name='permiso[<?php echo $p->id ?>]' type="checkbox" />
						 <?php echo $p->description ?>
						 <?php if ($p->id == 1):?> <b>(Mantener destildado para usuarios profesores)</b><?php endif ?>
				    </label>
				<?php endforeach ?>
			</div>
		</div>
		<div class='row'>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary"><?php l('aceptar')?></button>
					<a href="<?php echo base_url('/usuarios/')?>" class="btn"><?php l('cancelar')?></a>
				</div>
			</div>	
		</div>
	</form>
</section>
