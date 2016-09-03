<?
$tipo_opciones = [
    'texto'=>'texto libre', 
    'gantt'=> 'diagrama de gantt', 
    'calendario'=> 'calendario del diagrama de gantt'
];
$tipo = @$subseccion->tipo or 'texto';
?>
<script>
$(function() {
    $('form').submit(function() { 
        var errors = '';
        if ($("input#nombre_subseccion").val().length <= 3) { 
            errors = 'Debe seleccionar un valor para el nombre';
        }
        if (errors) { 
            alert(errors);
            return false;
        } else { 
            return true;
        }
    })
});
</script> 
<form action='<?= base_url("/proyectos/ajax_editar_subseccion") . "/$id_proyecto/$titulo/$id_subseccion"?>' method='post'>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Subsección</h3>
</div>
<div class="modal-body">
        <div class="control-group">
            <label class="control-label" for="nombre_subseccion">Nombre</label>
            <div class="controls">
                <input name='nombre' id="nombre_subseccion" class="input-large" type="text" placeholder="Nombre" value="<?php echo set_value('nombre', @$subseccion->nombre)?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nombre">Descripción</label>
            <div class="controls">
                <textarea name='descripcion'><?php echo set_value('descripcion', @$subseccion->descripcion)?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="nombre">Tipo</label>
            <div class="controls">
                <?= form_dropdown('tipo', $tipo_opciones, $tipo); ?>
            </div>
        </div>
</div>
<div class="modal-footer">
    <a href="#" class="btn" data-dismiss='modal'>Cerrar</a>
    <button class="btn btn-primary">Guardar cambios</button>
</div>
</form> 