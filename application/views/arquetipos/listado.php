<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<script src="<?php echo assets_url('js/clipboard.min.js');?>"></script>
<script type='text/javascript'> 
    $(document).ready(function() {
      /*  var oTable = $('#list').dataTable( {
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
                { "asSorting": [] },
            ]
        } );
        oTable.fnSort( [ [1,'desc'] ] );*/
    } );
</script>
<script>
    var clipboard = new Clipboard('.btnC');

    clipboard.on('success', function(e) {
        console.log(e);
        alert("Link copiado con éxito");
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
</script>
<section>
<div class="row">
	<div class="page-header col-md-12"><h1>Actividades</h1></div>
	
    <div class="col-md-12">
        <a class="btn btn-lg btn-default " href="<?php echo base_url('/admin')?>"><i class="fa fa-arrow-left"></i> Volver</a>
      <a class="btn btn-lg btn-default " href="<?php echo base_url('/arquetipos/editar/')?>"><i class="fa fa-plus"></i> Nueva actividad</a><br>
      <br>
      <br>

  </div>
<?php if (isset($ejercicios) && $ejercicios): ?>
<table class="table table-striped table-bordered table-hover" id='list'>
    <thead>
        <th width="25%">Nombre</th>
        <th width="8%">Fecha</th>
        <th width="15%">Autor</th>
        <th width="10%">Código público</th>
        <th width="10%">Respuestas (alumnos)</th>
        <th width="32%">Acciones</th>
    </thead>
    <tbody>
        <?php foreach ($ejercicios as $e): ?>
            <tr>
                <td><?php echo $e->nombre ?> </td>
                <td>
                    <span title='<?php echo strtotime($e->created_at)?>'>
                        <?php echo date('d/m/Y', strtotime($e->created_at))?>
                    </span>
                </td>
                <td><?php echo $e->autor ?></td>
                <td><span class="badge"><?php echo $e->id?></span></td>
                <td><span class="badge badge-success "><?php echo (int) $e->respuestas?> ( <?php echo (int) $e->alumnos?>  )</span></td>
                <td>
                    <!--
                    <?php if ($e->status == 'habilitado'): ?>
                        <a id="habilitarbt" class="btn btn-small btn-inverse" 
                            href='<?php echo base_url('/arquetipos/estado/deshabilitado/' . $e->id)?>'>
                            <i class="icon-thumbs-down icon-white"></i> deshabilitar
                        </a>
                    <?php else: ?>
                        <a id="habilitarbt" class="btn btn-small btn-success" 
                            href='<?php echo base_url('/arquetipos/estado/habilitado/' . $e->id)?>'>
                            <i class="icon-thumbs-up-alt icon-white"></i> habilitar
                        </a>
                    <?php endif; ?>


                    <a class="btn btn-small" href='<?php echo base_url('/alumnos/invitar/arquetipos/' . $e->id)?>'>
					<i class="icon-share"></i> invitar
                    </a>

                     <a class="btn btn-small"
                            href='<?php echo base_url('/arquetipos/link_publico/' . $e->id)?>'>
                             <i class="fa fa-eye"></i> previsualizar
                        </a>
                     -->
                    <a class="btn btn-info pull-left" href='<?php echo base_url('/arquetipos/duplicar/' . $e->id)?>'>
                        <i class="icon-copy"></i> Copiar y Editar
                    </a>
                    <a class="btn btn-success pull-left"
                        href='<?php echo base_url('/arquetipos/ver_respuestas/' . $e->id)?>'>
                        <i class="icon-list-alt"></i> Respuestas
                    </a>
                    <a class="btn btn-primary pull-left"
                        href='<?php echo base_url('/arquetipos/editar/' . $e->id)?>'>
                        <i class="icon-list-alt"></i> Editar
                    </a>

                    <a class="btn btn-danger pull-left"
                        href='<?php echo base_url('/arquetipos/borrar/' . $e->id)?>' 
                        onClick='return confirm("Confirme que quiere borrar este ejercicio");'>
                        <i class="fa fa-trash-o"></i> Eliminar
                    </a>

                    <?php if ($e->public_id_enabled): ?>
                        <a class="btn btn-primary pull-left" taget="_new" href='<?php echo base_url('/arquetipos/alumno_ejercicio/' . $e->public_id)?>'>
                           <i class="fa fa-external-link"></i> Link publico
                        </a>
                        <input style="left: -2000px; position: absolute"  type="text" id="copyme<?php echo $e->public_id?>" value="<?php echo base_url('/arquetipos/alumno_ejercicio/' . $e->public_id)?>" />
                        <button class="btnC btn btn-success pull-left" data-clipboard-action="copy" data-clipboard-target="#copyme<?php echo $e->public_id?>"> <i class="icon-copy"></i> Copiar Link</button>
                        <a class="btn btn-danger pull-left" href='<?php echo base_url('/arquetipos/publicar/' . $e->id . '/0')?>'>
                           <i class="fa fa-chain-broken"></i> Desactivar link publico
                        </a>
                    <?php else: ?>
                        <a class="btn btn-warning pull-left" href='<?php echo base_url('/arquetipos/publicar/' . $e->id . '/1')?>'>
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
<h3>No hay actividades cargadas.</h3>
<?php endif; ?>