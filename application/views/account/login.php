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

<form class="form-signin" method='POST' action='<?php echo base_url('account/validate')?>'>
	<input type="text" name='login' class="input-block-level" placeholder="Correo electrónico">
	<input type="password" name='password' class="input-block-level" placeholder="Clave">
	<p><a href="<?php echo base_url('/account/forgot')?>"> Olvidé mi clave</a></p>
	<button class="btn btn-large" type="submit">ACCEDER</button>
</form>