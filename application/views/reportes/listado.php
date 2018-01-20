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



<div class="col-md-12">
    <div class="blanco tablaGigante">
        <?php if (isset($reportes) && $reportes): ?>
            <table class="table table-striped table-bordered table-hover" id='list'>
                <thead>
                <th width="25%">Nombre</th>
                <th width="8%">Descripción</th>
                <th width="10%">Acciones</th>

                </thead>
                <tbody>
                <?php foreach ($reportes as $r): ?>
                    <tr>
                        <td><?php echo $r->nombre ?> </td>
                        <td> <?php echo $r->descripcion ?></td>

                        <td>
                            <a class="btn btn-large" href="<?php echo base_url('/reportes/ejecutarReporte/' . $r->store)?>">
                                <?php echo $r->etiqueta ?>
                            </a>
                            <a class="btn btn-large" href="<?php echo base_url('/reportes/seleccionarParametros/' . $r->id)?>">
                                Cambiar Parametros
                            </a>
                        </td>

                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

        <?php else: ?>
            <h3>No hay reportes cargados.</h3>
        <?php endif; ?>
    </div>
</div>
