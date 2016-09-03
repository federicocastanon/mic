<script type="text/javascript" src="<?php echo assets_url('js/ckeditor/ckeditor.js');?>"></script>

<div class="container">
<section>
    <div class="row-fluid">
                <?php echo validation_errors(); ?>
        <form method='post' class='form-horizontal'>
            <div class="page-header">
                <h1>Editar / Nuevo</h1>
            </div>
            <div class="control-group">
                <label class="control-label" for="nombre">Nombre</label>
                <div class="controls">
                    <input name='nombre' id="nombre" class="input-xxlarge" type="text" placeholder="Nombre del ejercicio - No se muestra al alumno" value="<?php echo set_value('nombre', @$dialogo->nombre)?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="consigna">Consigna</label>
                <div class="controls">
                    <input name='consigna' id="consigna" class="input-xxlarge" type="text" placeholder="Consigna del ejercicio" value="<?php echo set_value('consigna', @$dialogo->consigna)?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Desarrollo</label>
                <div class="controls">
                    <textarea name='desarrollo' style='height:500px;'> <?php echo set_value('desarrollo', @$dialogo->desarrollo)?> </textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Ideas claves</label>
                <div class="controls">
                    <input name="pregunta_1" class="input-xxlarge" type="text" placeholder="Idea 1" value="<?php echo set_value('pregunta_1', @$dialogo->pregunta_1)?>"><br><br>
                    <input name="pregunta_2" class="input-xxlarge" type="text" placeholder="Idea 2" value="<?php echo set_value('pregunta_2', @$dialogo->pregunta_2)?>"><br><br>
                    <input name="pregunta_3" class="input-xxlarge" type="text" placeholder="Idea 3" value="<?php echo set_value('pregunta_3', @$dialogo->pregunta_3)?>"><br><br>
                    <span class='help-block'>Les proponemos tener en cuenta las siguientes ideas claves para iniciar  la resoluci&oacute;n del ejercicio.</span>
                </div>
            </div>
            <div class='control-group'> 
                <button class="btn btn-primary btn-large pull-right" href="#">Continuar</button>
            </div>
        </form>
    </div>
</section>
</div>
<script type='text/javascript'>
    CKEDITOR.replace( 'desarrollo', {filebrowserUploadUrl: "<?php echo base_url('/arquetipos/upload_from_editor')?>"} );
</script>
<script src="<?php echo assets_url('js/vendor/jquery.ui.widget.js');?>"></script>
<script src="<?php echo assets_url('/js/jquery.fileupload.js');?>"></script>