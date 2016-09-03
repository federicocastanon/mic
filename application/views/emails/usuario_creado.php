<p>
	Usuario: <?php echo $email ?><br />
	Contraseña: <?php echo $password ?><br />
	<br />
	Por favor recuerda cambiar tu contraseña. 
	<br />
	Permisos asignados: <?= implode(',', $perms) ?>
	<br />
	Accedé a Citep Mic haciendo click <a href="<?= base_url()?>">aquí</a>
	<br />
	Por cualquier consulta, contáctate con nosotros a infomic@citep.rec.uba.ar
</p>