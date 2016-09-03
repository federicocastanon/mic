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
                { "asSorting": [] },
            ]
        } );
        oTable.fnSort( [ [1,'desc'] ] );
    } );
</script>

<section>
<div class="row-fluid">
	<div class="page-header"><h1>Proyectos</h1></div>

    <div class="span12">
      <? if ($this->user->has_permission('proyectos')): ?>
        <a class="btn btn-large pull-right" href="<?php echo base_url('/proyectos/nuevo/')?>"><i class="icon-plus"></i> nuevo proyecto</a><br>
      <? endif ?>
      <br>
      <br>

  </div>
<?php if (isset($proyectos) && $proyectos): ?>
<table class="table table-striped table-bordered table-hover" id='list'>
    <thead>
        <th width="181">Nombre</th>
        <th width="74">Fecha</th>
        <th width="127">Autor</th>
        <th width="131">Colaboradores</th>
        <th width="262">Acciones</th>
</thead>
    <tbody>
        <?php foreach ($proyectos as $p): ?>
            <tr>
                <td><?php echo $p->nombre ?> </td>
                <td>
                    <span title='<?php echo strtotime($p->created_at)?>'>
                        <?php echo date('d/m/Y', strtotime($p->created_at))?>
                    </span>
                </td>
                <td><?php echo $p->autor ?></td>
                <td><span class="badge"><?php echo (int) $p->colaboradores?></span></td>
                <td>
                    <? if ($this->user->has_permission('proyectos')): ?>
                        <?php if ($p->status == 'habilitado'): ?>
                            <a id="habilitarbt" class="btn btn-small btn-inverse" 
                                href='<?php echo base_url('/proyectos/estado/deshabilitado/' . $p->id)?>'>
                                <i class="icon-thumbs-down icon-white"></i> deshabilitar
                            </a>
                        <?php else: ?>
                            <a id="habilitarbt" class="btn btn-small btn-success" 
                                href='<?php echo base_url('/proyectos/estado/habilitado/' . $p->id)?>'>
                                <i class="icon-thumbs-up-alt icon-white"></i> habilitar
                            </a>
                        <?php endif; ?>
                        <a class="btn btn-small" href='<?php echo base_url('/proyectos/editar/' . $p->id)?>'>
                            <i class="fa fa-pencil"></i> secciones
                        </a>
                    <? endif ?>
                    <a class="btn btn-small btn-primary" href='<?php echo base_url('/proyectos/visualizar/' . $p->id)?>'>
                       <i class="fa fa-circle-o  icon-white"></i> editar proyecto
                    </a>
                    <a class="btn btn-small" href='<?php echo base_url('/proyectos/fichas/' . $p->id)?>'>
                        <i class="fa fa-file-o"></i> ver proyecto
                    </a>
                    <? if ($this->user->has_permission('proyectos')): ?>
                        <a class="btn btn-small" href='<?php echo base_url('/proyectos/colaboradores/' . $p->id)?>'>
    					   <i class="icon-share"></i> colaboradores
                        </a>
                        <a class="btn btn-small btn-danger" 
                            href='<?php echo base_url('/proyectos/borrar/' . $p->id)?>' 
                            onClick='return confirm("Confirme que quiere borrar este ejercicio");'>
                            <i class="fa fa-trash-o"></i> eliminar
                        </a>
                    <? endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table> 
<section>

<?php else: ?> 
<h3>No hay ejercicios cargados.</h3>
<?php endif; ?>
