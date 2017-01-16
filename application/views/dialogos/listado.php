<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<script type='text/javascript'> 
    $(document).ready(function() {
        var oTable = $('#list').dataTable( {
            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "bLengthChange": false,
            "aoColumns": [
                null,
                { "sType": "title-numeric" },
                null,
                null,
                null,
                null,
                { "asSorting": [] },
            ]
        } );
        oTable.fnSort( [ [1,'desc'] ] );
    } );
</script>

<section>
<div class="row-fluid">
	<div class="page-header"><h1>Ejercicios</h1></div>
	
    <div class="span12">
      <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogos/editar/')?>"><i class="fa fa-plus"></i> nuevo ejercicio</a><br>
      <br>
      <br>

  </div>
<?php if (isset($dialogos) && $dialogos): ?>
<table class="table table-striped table-bordered table-hover" id='list'>
    <thead>

        <th width="8%">Fecha</th>
        <th width="º0%">Autor</th>
        <th width="10%">Nombre</th>
        <th width="25%">Descripción</th>
        <th width="10%">Roles</th>
        <th width="10%">Dialogos</th>
        <th width="32%">Acciones</th>
    </thead>
    <tbody>
        <?php foreach ($dialogos as $e): ?>
            <tr>

                <td>
                    <span title='<?php echo strtotime($e->fecha)?>'>
                        <?php echo date('d/m/Y', strtotime($e->fecha))?>
                    </span>
                </td>
                <td><?php echo $e->autor ?></td>
                <td><?php echo $e->nombre ?> </td>
                <td><?php echo (int) $e->descripcion?></td>
                <td><b>Profesional: </b><?php echo $e->profesional?> <br>
                    <b>Secundario: </b><?php echo $e->secundario?>
                </td>
                <td><span class="badge "><?php echo (int) $e->dialogos?></span></td>
                <td>
                    <?php if ($e->status == 'habilitado'): ?>
                        <a id="habilitarbt" class="btn btn-small btn-inverse" 
                            href='<?php echo base_url('/dialogos/estado/deshabilitado/' . $e->id)?>'>
                            <i class="icon-thumbs-down icon-white"></i> deshabilitar
                        </a>
                    <?php else: ?>
                        <a id="habilitarbt" class="btn btn-small btn-success" 
                            href='<?php echo base_url('/dialogos/estado/habilitado/' . $e->id)?>'>
                            <i class="icon-thumbs-up-alt icon-white"></i> habilitar
                        </a>
                    <?php endif; ?>
                    <? if ($e->invitados == 0): ?> 
                        <a class="btn btn-small" href='<?php echo base_url('/dialogos/editar/' . $e->id)?>'>
                            editar
                        </a>
                    <? endif; ?>
                    <a class="btn btn-small" href='<?php echo base_url('/dialogos/duplicar/' . $e->id)?>'>
					   <i class="icon-copy"></i> copiar y editar
                    </a>
                    <a class="btn btn-small" href='<?php echo base_url('/alumnos/invitar/dialogos/' . $e->id )?>'>
					<i class="icon-share"></i> invitar
                    </a>
					<?php if ($e->invitados): ?>
                        <a class="btn btn-small btn-primary" 
                            href='<?php echo base_url('/dialogos/resumen_respuestas/' . $e->id)?>'>
                            <i class="icon-list-alt"></i> respuestas
                        </a>
                    <?php else: ?>
                        <a class="btn btn-small btn-primary" 
                            href='<?php echo base_url('/dialogos/editar/' . $e->id)?>'>
                            <i class="icon-list-alt"></i> editar
                        </a>
                    <?php endif; ?> 
                    <a class="btn btn-small btn-danger" 
                        href='<?php echo base_url('/dialogos/borrar/' . $e->id)?>' 
                        onClick='return confirm("Confirme que quiere borrar este ejercicio");'>
                        <i class="fa fa-trash-o"></i> eliminar
                    </a>
                    <? if ($e->public_id_enabled): ?>
                        <a class="btn btn-small" taget="_new" href='<?php echo base_url('/dialogos/link_publico/' . $e->public_id)?>'>
                           <i class="fa fa-external-link"></i> link publico
                        </a>
                        <a class="btn btn-small" href='<?php echo base_url('/dialogos/publicar/' . $e->id . '/0')?>'>
                            <i class="fa fa-chain-broken"></i> desactivar link publico
                        </a>
                    <? else: ?>
                        <a class="btn btn-small" href='<?php echo base_url('/dialogos/publicar/' . $e->id . '/1')?>'>
                           <i class="fa fa-external-link"></i> activar link publico
                        </a>
                    <? endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table> 
<section>

<?php else: ?> 
<h1>No hay ejercicios cargados todavia</h1>
<?php endif; ?>