<div class="container">
<section>
  <div class="row-fluid">
    <div class="page-header"><h1>Invitar alumnos</h1></div>
    <?php if (isset($error)) echo $error . '<br />'?>
        <form method='POST'>
            <div class="form-group">
                <label class="span2 control-label" for="textArea">Alumnos</label>
                <div class="span10">
                    <textarea name="invitados" rows="3" class="input-xlarge"  id="textArea"><?php echo $this->input->post('invitados');?></textarea>
                    <span class="help-block">Nombre + coma + Apellido + coma + su@email.com</span>
                    <span class="help-block">Ejemplo: Juan,Perez,jperez@gmail.com</span>
                    <span class="help-block">Si se trata de más de un usuario pulsar la tecla enter luego de cada línea de usuario.</span>
                </div>
            </div>
            <div class='form-group'>
                <button class='btn'>Invitar</button>
                <a href='<?= base_url('/' . $tipo)?>' class='btn'>Salir sin invitar</a>
            </div>
        </form>
    </div>
    </section>
</div>