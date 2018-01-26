<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<script src="<?php echo assets_url('js/clipboard.min.js');?>"></script>



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
                            <form method="post" action="<?php echo base_url('/reportes/ejecutarReporte/' . $r->id)?>">
                            <input type="submit" class="btn btn-large" value="<?php echo $r->etiqueta ?>"></input>
                           <input type="hidden" name="store" value="<?php echo $r->store ?>">
                            <input type="hidden" name="nombre" value="<?php echo $r->nombre ?>">
                            <input type="hidden" name="etiqueta" value="<?php echo $r->etiqueta ?>">

                            </form>
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
