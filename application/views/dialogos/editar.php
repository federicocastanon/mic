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
                    <input name='nombre' id="nombre" class="input-xxlarge" type="text" placeholder="Nombre del ejercicio - No se muestra al alumno" value="<?php echo set_value('nombre', @$prisma->nombre)?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="profesional">Profesional</label>
                <div class="controls">
                    <input name='profesional' id="profesional" class="input-xxlarge" type="text" placeholder="Nombre del Rol profesional" value="<?php echo set_value('profesional', @$prisma->profesional)?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="secundario">Secundario</label>
                <div class="controls">
                    <input name='secundario' id="secundario" class="input-xxlarge" type="text" placeholder="Nombre del rol Secundario" value="<?php echo set_value('secundario', @$prisma->secundario)?>">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Descripción</label>
                <div class="controls">
                    <textarea name='descripcion' style='height:500px;'> <?php echo set_value('descripcion', @$prisma->descripcion)?> </textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="dialogos">Dialogos</label>
                <div class="controls">
                    <input name='dialogos' id="dialogos" class="input-xxlarge" type="number" placeholder="Cantidad de Dialogos a crear" value="<?php echo set_value('dialogos', @$prisma->dialogos)?>">
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
    CKEDITOR.replace( 'descripcion', {filebrowserUploadUrl: "<?php echo base_url('/arquetipos/upload_from_editor')?>"} );
</script>
<script src="<?php echo assets_url('js/vendor/jquery.ui.widget.js');?>"></script>
<script src="<?php echo assets_url('/js/jquery.fileupload.js');?>"></script>