<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<script src="<?php echo assets_url('js/clipboard.min.js');?>"></script>
<script type='text/javascript'> 
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip()
    } );
</script>
<script>
    var clipboard = new Clipboard('.btnC');

    clipboard.on('success', function(e) {
        alert("Link copiado con éxito");
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>

<div class="col-md-12 ">
    <div class="col-md-12 botonera">
        <a class="btn btn-lg btn-default pull-right " href="<?php echo base_url('/admin')?>"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>
    <div class="col-md-12 botonera">
        <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/editar/')?>"><i class="fa fa-plus"></i> nuevo ejercicio</a><br>
    </div>
</div>
<div class="col-md-12">
    <div class="blanco tablaGigante">
<?php if (isset($prismas) && $prismas): ?>
<table class="table table-striped table-bordered table-hover" id='list'>
    <thead>

        <th width="9%">Fecha</th>
        <th width="8%">Autor</th>
        <th width="13%">Nombre</th>
        <th style="max-width: 20%;" width="30%">Descripción</th>
        <th width="15%">Roles</th>
        <th width="8%">Dialogos</th>
        <th width="7%">Código</th>
        <th width="10%">Acciones</th>
    </thead>
    <tbody>
        <?php foreach ($prismas as $e): ?>
            <tr>

                <td>
                    <span title='<?php echo strtotime($e->fecha)?>'>
                        <?php echo date('d/m/Y', strtotime($e->fecha))?>
                    </span>
                </td>
                <td><?php echo $e->autor ?></td>
                <td><?php echo $e->nombre ?> </td>
                <td style="overflow-x: scroll; max-width: 200px; width: 20%" ><?php echo $e->descripcion?></td>
                <td><b>Profesional: </b><?php echo $e->profesional?> <br>
                    <b>Secundario: </b><?php echo $e->secundario?>
                </td>
                <td><span class="badge "><?php echo (int) $e->dialogos?></span></td>
                <td><span class="badge "><?php echo $e->id?></span></td>
                <td>
                    <a class="pull-left" data-toggle="tooltip" title="Duplicar" href='<?php echo base_url('/dialogo/duplicar/' . $e->id)?>'>
                        <i class="linkListado fa fa-copy"></i>
                    </a>
                    <a class="pull-left" data-toggle="tooltip" title="Editar"
                       href='<?php echo base_url('/dialogo/editar/' . $e->id)?>'>
                        <i class="linkListado fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <a class="pull-left" data-toggle="tooltip" title="Eliminar"
                       href='<?php echo base_url('/dialogo/borrar/' . $e->id)?>'
                       onClick='return confirm("Confirme que quiere borrar este ejercicio. Todas las intervenciones y calificaciones de dialogos asociados a este ejercicio serán eliminados.");'>
                        <i class="linkListado fa fa-trash-o"></i>
                    </a>

                    <?php if ($e->publico): ?>
                        <a class="pull-left" data-toggle="tooltip" title="Ver Dialogos" taget="_new" href='<?php echo base_url('/dialogo/recepcionPrisma/' . $e->id)?>'>
                            <i class="linkListado fa fa-external-link"></i>
                        </a>
                        <input style="left: -2000px; position: absolute"  type="text" id="copyme<?php echo $e->id?>" value="<?php echo base_url('/dialogo/recepcionPrisma/' . $e->id)?>" />
                        <a class="btnC pull-left" data-toggle="tooltip" title="Copiar Link" data-clipboard-action="copy" data-clipboard-target="#copyme<?php echo $e->id?>"><i class="linkListado fa fa-clipboard"></i></a>
                        <a class="pull-left" data-toggle="tooltip" title="Ocultar" href='<?php echo base_url('/dialogo/publicar/' . $e->id . '/0')?>'>
                            <i class="linkListado fa fa-eye-slash"></i>
                        </a>
                    <?php else: ?>
                        <a class="pull-left" data-toggle="tooltip" title="Publicar" href='<?php echo base_url('/dialogo/publicar/' . $e->id . '/1')?>'>
                            <i class="linkListado fa fa-eye" aria-hidden="true"></i>
                        </a>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php else: ?> 
<h1>No hay actividades cargadas todavia</h1>
<?php endif; ?>
    </div>
</div>
    <div class="col-md-12">
        <div class="">
            <div class="col-md-12">
                <h3 class="referencia">Referencias:</h3>
            </div>
            <div class="col-md-12 col-sm-6">
                <span class="iconoReferencia"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editar</span>
                <span class="iconoReferencia"><i class="fa fa-external-link"></i> Link Publico</span>
                <span class="iconoReferencia"><i class="fa fa-eye" aria-hidden="true"></i> Publicar</span>
                <span class="iconoReferencia"> <i class="fa fa-clipboard"></i> Copiar Link</span>
                <span class="iconoReferencia"> <i class="fa fa-eye-slash"></i>Ocultar</span>
                <span class="iconoReferencia"><i class="fa fa-reply" aria-hidden="true"></i>Ver Respuestas</span>
                <span class="iconoReferencia"><i class="fa fa-copy"></i>Duplicar</span>
                <span class="iconoReferencia"><i class="fa fa-trash-o"></i> Eliminar </span>
            </div>
        </div>
    </div>