<div class="row-fluid">
    <div class="page-header"><h1>Diálogos</h1></div>

    <div class="span12">
        <?php if ($this->template_type == 'admin'): ?>
            <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/')?>"><i class="fa fa-arrow-left"></i> Volver</a>
        <?php else: ?>
            <a class="btn btn-lg btn-default pull-right" href="<?php echo base_url('/dialogo/recepcionPrisma/' . $prisma)?>"><i class="fa fa-arrow-left"></i> Volver</a>
        <?php endif; ?>


    </div>

</div>
<div class="row col-md-12"> Calificar como <b><?php  if (isset($_SESSION["alias"]))echo $_SESSION["alias"]?></b>
    <a class="btn btn-large" href="<?php echo base_url('/dialogo/cambiarAlias/' . $prisma)?>"> Cambiar Alias</a>
</div>

<div class="row col-md-12">
    <a class="btn btn-lg btn-warning pull-left" href="<?php echo base_url('/dialogo/verCalificaciones/' . $prisma)?>"><i class="fa fa-star"></i> Ver Valoraciones</a>
</div>
<?php if (isset($dialogos) && $dialogos): ?>
<div class="row bordeInferiorGrueso">
    <div class="col-sm-1 logolightxsmall">
        id
    </div>
    <div class="col-sm-4 logolightxsmall">
        ROL Profesional
    </div>
    <div class="col-sm-3 logolightxsmall">
        ROL secundario
    </div>
    <div class="col-sm-2 logolightxsmall">
        Estado
    </div>
    <div class="col-sm-2 logolightxsmall">
        Calificar
    </div>
</div>
<?php foreach ($dialogos as $e): ?>

<div class="row top30">
    <div class="col-sm-1 logolightxsmall">
        <span class="badge ">  <?php echo $e->id?></span>
    </div>
    <div class="col-sm-4">
        <?php if ($e->evaluado): ?>
            <?php echo $e->evaluado?>
         <?php endif; ?>
    </div>
    <div class="col-sm-3">
        <?php if ($e->secundario): ?>
            <?php echo $e->secundario?>
        <?php else: ?>
            <i>Quien estaba sentado acá se ha levantado. Quizás continúe el dialogo después.</i>
        <?php endif; ?>
    </div>

        <?php if ($e->terminado): ?>
            <div class="col-sm-2" style="color: red">
            FINALIZADO
        <?php elseif($e->evaluado && $e->secundario): ?>
            <div class="col-sm-2" style="color: green">
            EN CURSO
        <?php endif; ?>
            </div>

                <?php if  (isset($e->calificado) && $e->calificado): ?>
                    <div class="col-sm-2">
                       <i>CALIFICADO</i>
                    </div>
                <?php else: ?>
                    <?php if ($this->template_type == 'admin'): ?>
                        <div class="col-sm-2">
                            <a href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Calificar</a>
                        </div>
                    <?php else: ?>
                        <?php if ($_SESSION["alias"] != $e->evaluado and $_SESSION["alias"] != $e->secundario ): ?>
                            <div class="col-sm-2">
                                <a href="<?php echo base_url('/dialogo/calificar/'. $e->id)?>">Calificar</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>

        </div>

<?php endforeach ?>

        <?php else: ?>
            <h1>No hay dialogos para este PRISMA cargados todavia</h1>
        <?php endif; ?>

