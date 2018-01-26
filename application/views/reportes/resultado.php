<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>
<div class="col-md-12">
    <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url($volver)?>"><i class="fa fa-arrow-left"></i> Volver</a>
</div>
<div class="col-md-12">
    <h2><?php echo $titulo?></h2>
    <div class="blanco tablaGigante">
        <?php if (isset($respuesta) && $respuesta): ?>
            <table class="table table-striped table-bordered table-hover" id='list'>
                <thead>
                <?php foreach ($columnas as $c): ?>


                <th><?php echo $c ?></th>

                <?php endforeach ?>
                </thead>
                <tbody>
                <?php foreach ($respuesta as $r): ?>
                    <tr>
                    <?php foreach ($columnas as $c): ?>
                        <td><?php echo $r->$c ?> </td>
                    <?php endforeach ?>

                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

        <?php else: ?>
            <h3>No hay reportes cargados.</h3>
        <?php endif; ?>
    </div>
</div>
