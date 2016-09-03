<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="<?php echo site_url('/admin/')?>">Citep mic</a>
      <div class="nav-collapse collapse">
        <ul class="nav pull-left">
          <?php if ($this->user->has_permission('arquetipos')) : ?>
            <li <?php if ($_location == 'arquetipos') { ?>class="active"<?php } ?>>
              <a class="navfocos" href="<?php echo site_url('/arquetipos/')?>"><?php l('Focos en juego')?></a>
            </li>
          <?php endif ?>
          <?php if ($this->user->has_permission('dialogos')) : ?>
            <li <?php if ($_location == 'dialogos') { ?>class="active"<?php } ?>>
              <a class="navprismas" href="<?php echo site_url('/dialogos/')?>"><?php l('Prismas entramados')?></a>
            </li>
          <?php endif ?>
          <?php if ($this->user->has_permission('proyectos') or $this->user->has_permission('proyecto_colaborador')) : ?>
            <li <?php if ($_location == 'proyectos') { ?>class="active"<?php } ?>>
              <a class="navcroquis" href="<?php echo site_url('/proyectos/')?>"><?php l('Croquis en movimiento')?></a>
            </li>
          <?php endif ?>
        </ul>    
	<ul class="nav navbar-nav pull-right">
      <?php if ($_is_admin): ?>
        	<li <?php if ($_location == 'usuarios') { ?>class="active"<?php } ?>>
            	<a href="<?php echo site_url('/usuarios/')?>"><?php l('Usuarios')?></a>
          	</li>
      <?php endif; ?>
            
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo base_url('/account/me')?>" ><?php echo $this->user->get_name(); ?><span class="caret"></span></a>
                <ul class="dropdown-menu">
					<li>
					<a href='<?php echo site_url('/account/edit')?>'>Editar mi cuenta</a>
					</li>        
          <li>
          <a href='<?php echo site_url('/account/change_password')?>'>Cambiar contrase&ntilde;a</a>
        </li>
					<li>
					<a href='<?php echo site_url('/account/logout')?>'>Desconectar</a>
					</li>
				</ul>             
            </li>
             
            <li><a href="http://citep.rec.uba.ar" target="_blank">Citep</a></li>
          </ul>
	</div><!--/.nav-collapse -->
    </div>
  </div>
</div>
