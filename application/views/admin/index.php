<div class="container">
	<section id="logos">
		<div class="row-fluid">
			<?php if ($this->user->has_permission('arquetipos')) : ?>
				<a href='<?php echo base_url('/arquetipos/')?>'>
					<div id="logo" class="span4">
						<span class="logoxsmall">Focos</span>
						<span class="logolightxsmall"> en juego</span><br /> 
						<img src="<?= assets_url('/img/foco.png')?>" width="116" height="120" border="0">
					</div>
				</a>
			<?php endif ?>
			<?php if ($this->user->has_permission('dialogos')) : ?>
				<a href='<?php echo base_url('/dialogo/')?>'>
					<div id="logo" class="span4">
						<span class="logoxsmall">Prismas</span>
						<span class="logolightxsmall"> entramados</span><br /> 
						<img src="<?= assets_url('/img/prismas.png')?>" width="116" height="120" border="0">
					</div>
				</a>
			<?php endif ?>
			<?php if ($this->user->has_permission('proyectos') or $this->user->has_permission('proyecto_colaborador')) : ?>
				<a href='<?php echo base_url('/proyectos/')?>'>
					<div id="logo" class="span4">
						<span class="logoxsmall">Croquis</span>
						<span class="logolightxsmall"> en movimiento</span><br /> 
						<img src="<?= assets_url('/img/croquis.png')?>" width="116" height="120" border="0">
					</div>
				</a>
			<?php    endif ?>
            <?php if ($this->user->has_permission('admin')) : ?>
                <a href='<?php echo base_url('/reportes/')?>'>
                    <div id="logo" class="span4">
                        <span class="logoxsmall">Reportes</span><br />
                    </div>
                </a>
            <?php endif ?>


		</div>
	</section>
</div>