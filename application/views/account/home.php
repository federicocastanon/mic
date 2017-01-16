<div style="float: right;">
    <a href="<?php echo base_url('/account/index')?>">Ingreso Docente</a>
</div>
<div style="float: right;">
    <a href="<?php echo base_url('/dialogo/testing')?>">TEST CONTROLLER</a>
</div>

<form method='POST' action='<?php echo base_url('arquetipos/ingresoAlumno')?>'>
       <div id="logo" class="span4">
            <span class="logoxsmall">Focos en juego</span><br />
            <img src="<?= assets_url('/img/foco.png')?>" width="116" height="120" border="0">
           <input type="text" name='id' class="input-block-level" placeholder="Código ejercicio">
           <button class="btn btn-large" type="submit">ACCEDER</button>
        </div>

</form>