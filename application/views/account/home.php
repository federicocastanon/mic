
<div style="float: right;">
    <a href="<?php echo base_url('/account/index')?>">Ingreso Docente</a>
</div>

<div class="row top30">
    <div class="col-sm-4">
        <form method='POST' action='<?php echo base_url('arquetipos/ingresoAlumno')?>'>
               <div id="logo" class="span4">
                    <span class="logoxsmall">Focos en juego</span><br />
                    <img src="<?= assets_url('/img/foco.png')?>" width="116" height="120" border="0">
                   <input type="text" name='alias' class="input-block-level" placeholder="Alias para identificarte" >
                   <input type="text" name='id' class="input-block-level" placeholder="Código ejercicio" required="true">
                   <button class="btn btn-large" type="submit">ACCEDER</button>
                </div>
        </form>
    </div>
    <div class="col-sm-4">
        <form method='POST' action='<?php echo base_url('/dialogo/recepcionPrisma/')?>'>
            <div id="logo" class="span4">
                <span class="logoxsmall">Prismas</span><br />
                <img src="<?= assets_url('/img/prismas.png')?>" width="116" height="120" border="0">
                <input type="text" name='alias' class="input-block-level" placeholder="Alias para identificarte" >
                <input type="text" name='id' class="input-block-level" placeholder="Código ejercicio" required="true">
                <button class="btn btn-large" type="submit">ACCEDER</button>
            </div>
        </form>
    </div>
</div>
