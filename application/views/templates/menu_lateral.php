<nav id="menuL">
    <ul>
        <li><a href="<?php echo site_url('/arquetipos/')?>">Focos en juego</a></li>
        <li><a href="<?php echo site_url('/dialogo/')?>">Prismas entramados</a></li>
        <li><a href="<?php echo site_url('/proyectos/')?>">Croquis en movimiento</a></li>
        <li><a href="<?php echo site_url('/usuarios/')?>">Usuarios</a></li>
        <li><span><?php echo $this->user->get_name(); ?></span>
            <ul>
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
        <li><a href="http://citep.rec.uba.ar">CITEP UBA</a></li>
        <li><a href="<?php echo site_url('/admin/')?>">Citep</a></li>
    </ul>
</nav>