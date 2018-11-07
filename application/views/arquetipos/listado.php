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
        console.log(e);
        alert("Link copiado con éxito");
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
      <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/arquetipos/editar/')?>"><i class="fa fa-plus"></i> Nueva actividad</a><br>
            </div>
  </div>

<div class="col-md-12">
    <div class="blanco tablaGigante">
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
                    <a class="pull-left" data-toggle="tooltip" title="Duplicar" href='<?php echo base_url('/arquetipos/duplicar/' . $e->id)?>'><i class="linkListado fa fa-copy"></i></a>

                    <a class="pull-left" data-toggle="tooltip" title="Editar"
                        href='<?php echo base_url('/arquetipos/editar/' . $e->id)?>'><i class="linkListado fa fa-pencil-square-o" aria-hidden="true"></i></a>

                    <a class="pull-left" data-toggle="tooltip" title="Eliminar"
                        href='<?php echo base_url('/arquetipos/borrar/' . $e->id)?>'onClick='return confirm("Confirme que quiere borrar este ejercicio");'><i class="linkListado fa fa-trash-o"></i></a>

                    <a class="pull-left" data-toggle="tooltip" title="Ver Respuestas"
                       href='<?php echo base_url('/arquetipos/ver_respuestas/' . $e->id)?>'><i class="linkListado fa fa-reply" aria-hidden="true"></i></a>

                    <?php if ($e->public_id_enabled): ?>

                        <a class="pull-left" data-toggle="tooltip" title="Link Publico" taget="_new" href='<?php echo base_url('/arquetipos/alumno_ejercicio/' . $e->id)?>'><i class="linkListado fa fa-external-link"></i></a>
                        <input style="left: -2000px; position: absolute"  type="text" id="copyme<?php echo $e->id?>" value="<?php echo base_url('/arquetipos/alumno_ejercicio/' . $e->id)?>" />
                        <a class="btnC pull-left" data-toggle="tooltip" title="Copiar Link" data-clipboard-action="copy" data-clipboard-target="#copyme<?php echo $e->id?>"><i class="linkListado fa fa-clipboard"></i></a>
                        <a class="pull-left" data-toggle="tooltip" title="Ocultar" href='<?php echo base_url('/arquetipos/publicar/' . $e->id . '/0')?>'><i class="linkListado fa fa-eye-slash"></i></a>
                    <?php else: ?>
                        <a class="pull-left" data-toggle="tooltip" title="Publicar" href='<?php echo base_url('/arquetipos/publicar/' . $e->id . '/1')?>'><i class="linkListado fa fa-eye" aria-hidden="true"></i></a>
                    <?php endif ?>
                    <a class="pull-left" data-toggle="tooltip" title="Transferir" href='<?php echo base_url('/arquetipos/transferir/' . $e->id)?>'><i class="linkListado fa fa-key" aria-hidden="true"></i></a>

                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php else: ?> 
<h3>No hay actividades cargadas.</h3>
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
        <span class="iconoReferencia"><i class="fa fa-key"></i> Transferir ejercicio </span>
    </div>
    </div>
</div>
