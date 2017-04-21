<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<script src="<?php echo assets_url('js/clipboard.min.js');?>"></script>
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
                null,
                { "asSorting": [] },
            ]
        } );
        oTable.fnSort( [ [0,'desc'] ] );
    } );
</script>
<script>
    var clipboard = new Clipboard('.btnC');

    clipboard.on('success', function(e) {
        console.log(e);
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>

<section>
<div class="row-fluid">
	<div class="page-header"><h1>Actividades</h1></div>
	
    <div class="span12">
      <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/editar/')?>"><i class="fa fa-plus"></i> nuevo ejercicio</a><br>
      <br>
      <br>

  </div>
<?php if (isset($prismas) && $prismas): ?>
<table class="table table-striped table-bordered table-hover" id='list'>
    <thead>

        <th width="8%">Fecha</th>
        <th width="10%">Autor</th>
        <th width="10%">Nombre</th>
        <th width="25%">Descripción</th>
        <th width="10%">Roles</th>
        <th width="5%">Dialogos</th>
        <th width="5%">Código</th>
        <th width="32%">Acciones</th>
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
                <td><?php echo $e->descripcion?></td>
                <td><b>Profesional: </b><?php echo $e->profesional?> <br>
                    <b>Secundario: </b><?php echo $e->secundario?>
                </td>
                <td><span class="badge "><?php echo (int) $e->dialogos?></span></td>
                <td><span class="badge "><?php echo $e->id?></span></td>
                <td>
                    <a class="btn btn-info pull-left" href='<?php echo base_url('/dialogo/duplicar/' . $e->id)?>'>
                        <i class="icon-copy"></i> Copiar y Editar
                    </a>
                    <a class="btn btn-primary pull-left"
                       href='<?php echo base_url('/dialogo/editar/' . $e->id)?>'>
                        <i class="icon-list-alt"></i> Editar
                    </a>
                    <a class="btn btn-danger pull-left"
                       href='<?php echo base_url('/dialogo/borrar/' . $e->id)?>'
                       onClick='return confirm("Confirme que quiere borrar este ejercicio. Todas las intervenciones y calificaciones de dialogos asociados a este ejercicio serán eliminados.");'>
                        <i class="fa fa-trash-o"></i> Eliminar
                    </a>

                    <?php if ($e->publico): ?>
                        <a class="btn btn-primary pull-left" taget="_new" href='<?php echo base_url('/dialogo/dialogosPorPrisma/' . $e->id)?>'>
                            <i class="fa fa-external-link"></i> Ir al Lobby
                        </a>
                        <input style="left: -2000px; position: absolute"  type="text" id="copyme<?php echo $e->id?>" value="<?php echo base_url('/dialogo/dialogosPorPrisma/' . $e->id)?>" />
                        <button class="btnC btn btn-success pull-left" data-clipboard-action="copy" data-clipboard-target="#copyme<?php echo $e->id?>"> <i class="icon-copy"></i> Copiar Link</button>
                        <a class="btn btn-danger pull-left" href='<?php echo base_url('/dialogo/publicar/' . $e->id . '/0')?>'>
                            <i class="fa fa-chain-broken"></i> Desactivar link publico
                        </a>
                    <?php else: ?>
                        <a class="btn btn-warning pull-left" href='<?php echo base_url('/dialogo/publicar/' . $e->id . '/1')?>'>
                            <i class="fa fa-external-link"></i> Activar link publico
                        </a>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table> 
<section>

<?php else: ?> 
<h1>No hay actividades cargadas todavia</h1>
<?php endif; ?>