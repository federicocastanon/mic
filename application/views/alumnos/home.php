<style type="text/css">
    body {
        padding-top: 50px;
        padding-bottom: 40px;
        background-image: url(../../../assets/img/bg.jpg);
        background-repeat: no-repeat;
        background-position: center top;
    }

    .form-signin {
        max-width: 300px;
        padding: 30px 29px 29px;
        margin: 0 auto 20px;

    }
    .form-signin .form-signin-heading,
    .form-signin .checkbox {
        margin-bottom: 10px;
    }
    .form-signin input[type="text"],
    .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
    }
</style>

<form method='POST' action='<?php echo base_url('arquetipos/ingresoAlumno')?>'>
       <div id="logo" class="span4">
            <span class="logoxsmall">Focos</span>
            <span class="logolightxsmall"> en juego</span><br />
            <img src="<?= assets_url('/img/foco.png')?>" width="116" height="120" border="0">
        </div>
    <input type="text" name='id' class="input-block-level" placeholder="Código ejercicio">
    <button class="btn btn-large" type="submit">ACCEDER</button>
</form>