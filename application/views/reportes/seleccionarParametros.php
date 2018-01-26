<script src="<?php echo assets_url('/js/jquery.dataTables.js')?>"></script>
<script src="<?php echo assets_url('/js/bootstrap-datatables.js')?>"></script>

<div class="col-md-12">
    <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('reportes/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
</div>


<div class="col-md-12">
    <div class="blanco tablaGigante">
        <form method="post" action="<?php echo base_url('/reportes/ejecutarReporteConParametros/' . $reporte->id) ?>">
            <table class="table table-striped table-bordered table-hover" id='list'>
                <thead>
                <th width="25%">Nombre</th>
                <th width="8%">Formato</th>
                <th width="10%">Valores</th>

                </thead>

                <tbody>

                <input type="hidden" name="store" value="<?php echo $reporte->store ?>">
                <input type="hidden" name="nombre" value="<?php echo $reporte->nombre ?>">
                <?php foreach ($parametros as $p): ?>
                    <tr>
                        <td><?php echo $p->eti ?> </td>
                        <td> <?php echo $p->tipo ?></td>

                        <td>

                            <input type="text" required class="required" value="" name="parametros[]"></input>

                        </td>

                    </tr>
                <?php endforeach ?>

                </tbody>

            </table>
            <input type="submit" value="GENERAR REPORTE">
        </form>
    </div>
</div>
